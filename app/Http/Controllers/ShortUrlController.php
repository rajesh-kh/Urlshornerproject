<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('Admin')) {
            $list = ShortUrl::where('company_id', '!=', $user->company_id)->get();
        } elseif ($user->hasRole('Member')) {
            $list = ShortUrl::where('created_by', '!=', $user->id)->get();
        } elseif ($user->hasRole('SuperAdmin')) {
            $list = collect(); // SuperAdmin cannot see all
        } else {
            // Sales, Manager and others: show their company urls
            $list = ShortUrl::where('company_id', $user->company_id)->get();
        }

        return response()->json($list);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // SuperAdmin, Admin, Member cannot create
        if ($user->hasRole('SuperAdmin') || $user->hasRole('Admin') || $user->hasRole('Member')) {
            return response()->json(['message' => 'Forbidden'], 403);
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

        return response()->json($short, 201);
    }

    public function resolve($slug, Request $request)
    {
        $user = $request->user();
        $short = ShortUrl::where('slug', $slug)->firstOrFail();

        // short urls are not publicly resolvable
        if (! $user) {
            abort(403);
        }

        return redirect()->away($short->original_url);
    }
}
