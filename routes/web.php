<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::get('/dashboard', function (Request $request) {
    $user = auth()->user();
    // common stats
    $total = \App\Models\ShortUrl::count();
    $byCompany = $user ? \App\Models\ShortUrl::where('company_id', $user->company_id)->count() : 0;
    $createdByMe = $user ? \App\Models\ShortUrl::where('created_by', $user->id)->count() : 0;
    $roles = $user ? $user->roles->pluck('name')->toArray() : [];

    $range = $request->query('range', 'this_month');

    // SuperAdmin gets a richer dashboard
    if ($user && $user->hasRole('SuperAdmin')) {
        $companies = \App\Models\Company::withCount('users')->get()->map(function ($c) {
            $totalUrls = \App\Models\ShortUrl::where('company_id', $c->id)->count();
            $totalHits = \App\Models\ShortUrl::where('company_id', $c->id)->sum('hits');
            return (object)[
                'id' => $c->id,
                'name' => $c->name,
                'users_count' => $c->users_count,
                'total_urls' => $totalUrls,
                'total_hits' => $totalHits,
            ];
        });

        // timeframe filter
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

        $generated = \App\Models\ShortUrl::whereBetween('created_at', [$from, $to])->with('company')->orderByDesc('created_at')->get();

        return view('superadmin.dashboard', compact('companies','generated','range'));
    }

    // Client Admin dashboard
    if ($user && $user->hasRole('Admin')) {
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

        $generated = \App\Models\ShortUrl::where('company_id', $user->company_id)->whereBetween('created_at', [$from, $to])->with('creator')->orderByDesc('created_at')->get();

        // team members
        $members = \App\Models\User::where('company_id', $user->company_id)->with('roles')->get()->map(function ($m) {
            $totalUrls = \App\Models\ShortUrl::where('created_by', $m->id)->count();
            $totalHits = \App\Models\ShortUrl::where('created_by', $m->id)->sum('hits');
            return (object)[
                'id' => $m->id,
                'name' => $m->name,
                'email' => $m->email,
                'role' => $m->roles->pluck('name')->first(),
                'total_urls' => $totalUrls,
                'total_hits' => $totalHits,
            ];
        });

        return view('client.admin_dashboard', compact('generated','range','members'));
    }

    // Client Member dashboard
    if ($user && $user->hasRole('Member')) {
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

        $generated = \App\Models\ShortUrl::where('company_id', $user->company_id)->whereBetween('created_at', [$from, $to])->orderByDesc('created_at')->get();

        return view('client.member_dashboard', compact('generated','range'));
    }

    return view('dashboard', compact('total','byCompany','createdByMe','roles'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Short URL UI
    Route::get('/short-urls', [\App\Http\Controllers\ShortUrlController::class, 'index']);
    Route::get('/short-urls/create', [\App\Http\Controllers\ShortUrlController::class, 'create']);
    Route::post('/short-urls', [\App\Http\Controllers\ShortUrlController::class, 'store'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    Route::get('/short-urls/download', [\App\Http\Controllers\ShortUrlController::class, 'download']);
    // short URL resolve route moved outside auth so controller can decide access

    // Client invites (SuperAdmin)
    Route::get('/clients/invite', [\App\Http\Controllers\ClientController::class, 'invite'])->name('clients.invite');
    Route::post('/clients/invite', [\App\Http\Controllers\ClientController::class, 'sendInvite'])->name('clients.invite.send');
    // Team invites (Client Admin)
    Route::get('/team/invite', [\App\Http\Controllers\ClientController::class, 'inviteMember'])->name('team.invite');
    Route::post('/team/invite', [\App\Http\Controllers\ClientController::class, 'sendMemberInvite'])->name('team.invite.send');
});

// Public resolve route (controller enforces that only authenticated users may resolve)
Route::get('/s/{slug}', [\App\Http\Controllers\ShortUrlController::class, 'resolve']);

require __DIR__.'/auth.php';
