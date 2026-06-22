<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShortUrlController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('Admin')) {
            // Admins see URLs for their company
            $list = ShortUrl::where('company_id', $user->company_id)->get();
        } elseif ($user->hasRole('Member')) {
            // Members see URLs they created
            $list = ShortUrl::where('created_by', $user->id)->get();
        } elseif ($user->hasRole('SuperAdmin')) {
            $list = collect(); // SuperAdmin cannot see all
        } else {
            // Sales, Manager and others: show their company urls
            $list = ShortUrl::where('company_id', $user->company_id)->get();
        }

        // search
        $q = $request->query('q');
        if ($q) {
            $list = $list->filter(function ($item) use ($q) {
                return str_contains($item->original_url, $q) || str_contains($item->slug, $q);
            })->values();
        }

        if ($request->wantsJson()) {
            return response()->json($list);
        }

        return view('short_urls.index', ['shortUrls' => $list, 'q' => $q]);
    }

    public function create(Request $request)
    {
        return view('short_urls.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Only SuperAdmin is not allowed to create short URLs here
        if ($user->hasRole('SuperAdmin')) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            return redirect()->back()->with('error', 'You are not allowed to create short URLs.');
        }

        $data = $request->validate([
            'original_url' => 'required|url',
        ]);

        $slug = Str::random(8);

        $short = ShortUrl::create([
            'slug' => $slug,
            'original_url' => $data['original_url'],
            'company_id' => $user->company_id,
            'created_by' => $user->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json($short, 201);
        }

        return redirect('/short-urls')->with('success', 'Short URL created.');
    }

    public function resolve($slug, Request $request)
    {
        $user = $request->user();
        $short = ShortUrl::where('slug', $slug)->firstOrFail();

        // short urls are not publicly resolvable
        if (! $user) {
            abort(403);
        }

        // increment hit counter
        try {
            $short->increment('hits');
        } catch (\Exception $e) {
            // ignore increment failures
        }

        return redirect()->away($short->original_url);
    }

    public function show($id, Request $request)
    {
        $short = ShortUrl::findOrFail($id);
        return view('short_urls.show', ['short' => $short]);
    }

    public function destroy($id, Request $request)
    {
        $user = $request->user();
        $short = ShortUrl::findOrFail($id);

        // only creator, managers or sales from same company can delete
        if ($short->created_by !== $user->id && $user->company_id !== $short->company_id) {
            return redirect()->back()->with('error', 'Not allowed');
        }

        $short->delete();
        return redirect('/short-urls')->with('success', 'Deleted');
    }

    public function download(Request $request)
    {
        $user = $request->user();
        $range = $request->query('range');

        if ($user->hasRole('Admin')) {
            $query = ShortUrl::where('company_id', $user->company_id);
        } elseif ($user->hasRole('Member')) {
            $query = ShortUrl::where('created_by', $user->id);
        } elseif ($user->hasRole('SuperAdmin')) {
            $query = ShortUrl::query();
        } else {
            $query = ShortUrl::where('company_id', $user->company_id);
        }

        if ($range) {
            $from = now()->startOfMonth();
            $to = now()->endOfMonth();
            if ($range === 'last_month') {
                $from = now()->subMonthNoOverflow()->startOfMonth();
                $to = now()->subMonthNoOverflow()->endOfMonth();
            } elseif ($range === 'last_week') {
                $from = now()->subWeek()->startOfWeek();
                $to = now()->subWeek()->endOfWeek();
            } elseif ($range === 'today') {
                $from = now()->startOfDay();
                $to = now()->endOfDay();
            }
            $query->whereBetween('created_at', [$from, $to]);
        }

        $list = $query->get();

        $response = new StreamedResponse(function () use ($list) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['slug', 'original_url', 'hits', 'company_id', 'created_by', 'created_at']);
            foreach ($list as $row) {
                fputcsv($handle, [$row->slug, $row->original_url, $row->hits ?? 0, $row->company_id, $row->created_by, $row->created_at]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="short_urls.csv"');

        return $response;
    }
}
