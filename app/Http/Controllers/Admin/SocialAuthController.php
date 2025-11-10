<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialAuth;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
    public function index()
    {
        $socials = SocialAuth::all();

        return view('admin.social-auth.index', compact('socials'));
    }

    public function update(SocialAuth $socialAuth, Request $request)
    {
        $socialAuth->update([
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
            'redirect' => $request->redirect,
        ]);

        return back()->with('success', __('Updated Successfully'));
    }

    public function toggle(SocialAuth $socialAuth)
    {
        $socialAuth->update([
            'is_active' => ! $socialAuth->is_active,
        ]);

        return back()->with('success', __('Status updated successfully'));
    }
}
