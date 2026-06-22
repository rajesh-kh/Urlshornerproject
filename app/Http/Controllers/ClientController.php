<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class ClientController extends Controller
{
    public function invite()
    {
        return view('clients.invite');
    }

    public function sendInvite(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        // Minimal invite implementation: create a placeholder company record
        Company::create(['name' => $data['name']]);

        return redirect()->route('dashboard')->with('success', 'Invitation sent.');
    }

    public function inviteMember()
    {
        return view('clients.invite_member');
    }

    public function sendMemberInvite(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|in:Member,Admin',
        ]);

        // create user and attach to current company
        $password = \Illuminate\Support\Str::random(12);
        $new = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $password,
            'company_id' => $user->company_id,
        ]);

        $role = \App\Models\Role::where('name', $data['role'])->first();
        if ($role) {
            $new->roles()->attach($role->id);
        }

        return redirect()->route('dashboard')->with('success', 'Team member invited.');
    }
}
