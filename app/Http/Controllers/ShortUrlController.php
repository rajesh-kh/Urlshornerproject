<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShortUrlController extends Controller
{
    public function create(Request $request)
    {
        return view('short_urls.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Allow Sales, Manager, Admin and Member roles to create short URLs
        if (! $user->hasRole('Sales') && ! $user->hasRole('Manager') && ! $user->hasRole('Admin') && ! $user->hasRole('Member')) {
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

        return redirect('/dashboard')->with('success', 'Short URL created.');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('SuperAdmin')) {
            // SuperAdmin: see everything
            $list = ShortUrl::all();
        } elseif ($user->hasRole('Admin')) {
            // Admin: only see short URLs for their own company
            $list = ShortUrl::where('company_id', $user->company_id)->get();
        } elseif ($user->hasRole('Member')) {
            // Member: only see short URLs created by themselves
            $list = ShortUrl::where('created_by', $user->id)->get();
        } else {
            // Default: empty collection
            $list = collect();
        }

        return response()->json($list);
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
