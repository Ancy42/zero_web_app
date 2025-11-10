<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerifyManage;
use App\Repositories\VerifyManageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VerifyManageController extends Controller
{
    public function index()
    {
        $verifyManage = VerifyManage::latest('id')->first();

        return view('admin.verification.index', compact('verifyManage'));
    }

    public function update(Request $request)
    {
        VerifyManageRepository::updateOrCreateByRequest($request);

        Cache::forget('verify_manage');

        return to_route('admin.verification.index')->withSuccess(__('Updated Successfully'));
    }
}
