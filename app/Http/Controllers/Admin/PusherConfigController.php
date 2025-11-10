<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PusherConfigController extends Controller
{
    /*
    * pusher index page
    */
    public function index()
    {
        return view('admin.pusher-config');
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_id' => 'required',
            'app_key' => 'required',
            'app_secret' => 'required',
        ]);

        try {
            $this->setEnv('PUSHER_APP_ID', $request->app_id);
            $this->setEnv('PUSHER_APP_KEY', $request->app_key);
            $this->setEnv('PUSHER_APP_SECRET', $request->app_secret);
            $this->setEnv('PUSHER_APP_CLUSTER', $request->app_cluster);

            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return to_route('admin.pusher.index')->with('success', __('Pusher config updated successfully'));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
