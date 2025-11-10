<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneraleSettingRequest;
use App\Models\GeneraleSetting;
use App\Repositories\GeneraleSettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BusinessSetupController extends Controller
{
    /**
     * Display a listing of the business settings.
     */
    public function index()
    {
        $timezones = [];
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $timezones[$key]['zone'] = $zone;
            $timezones[$key]['diff_from_GMT'] = 'UTC/GMT '.date('P', $timestamp);
        }

        return view('admin.business-setup.index', compact('timezones'));
    }

    /**
     * Update the business settings.
     */
    public function update(GeneraleSettingRequest $request)
    {
        GeneraleSettingRepository::updateOrCreateByRequest($request);

        if ($request->timezone != config('app.timezone')) {

            $response = $this->setEnv('APP_TIMEZONE', $request->timezone);

            if ($response['type'] == 'error') {
                return back()->with('alertError', [
                    'message' => 'Business settings updated but timezone not updated in .env file because '.$response['message'],
                    'message2' => 'Please change your .env file permission, set permission to 0777 and try again',
                ]);
            } else {
                Artisan::call('config:clear');
                Artisan::call('cache:clear');
            }
        }

        return back()->withSuccess(__('Business settings updated successfully'));
    }

    /**
     * shop info settings
     */
    public function shop()
    {
        return view('admin.business-setup.shop');
    }

    /**
     * toggle pos
     */
    public function togglePOS()
    {
        $generaleSetting = GeneraleSetting::first();

        $generaleSetting?->update([
            'shop_pos' => ! $generaleSetting->shop_pos,
        ]);

        return back()->withSuccess(__('POS status updated successfully'));
    }

    /**
     * toggle register
     */
    public function toggleRegister()
    {
        $generaleSetting = GeneraleSetting::first();

        $generaleSetting->update([
            'shop_register' => ! $generaleSetting->shop_register,
        ]);

        return back()->withSuccess(__('Register status updated successfully'));
    }

    /**
     * update shop info
     */
    public function shopUpdate(Request $request)
    {
        GeneraleSettingRepository::updateOrCreateShopSetting($request);

        return back()->withSuccess(__('Shop info updated successfully'));
    }

    /**
     * withdraw settings
     */
    public function withdraw()
    {
        return view('admin.business-setup.withdraw');
    }

    /**
     * update withdraw settings
     */
    public function withdrawUpdate(Request $request)
    {
        GeneraleSettingRepository::updateOrCreateWithdrawSetting($request);

        return back()->withSuccess(__('Withdraw settings updated successfully'));
    }
}
