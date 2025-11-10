<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoogleReCaptchaRequest;
use App\Models\GoogleReCaptcha;
use App\Repositories\GoogleReCaptchaRepository;

class GoogleReCaptchaController extends Controller
{
    public function index()
    {
        $reCaptcha = GoogleReCaptcha::first();

        return view('admin.google-recaptcha', compact('reCaptcha'));
    }

    public function update(GoogleReCaptchaRequest $request)
    {
        $reCaptcha = GoogleReCaptcha::first();

        GoogleReCaptchaRepository::updateByRequest($request, $reCaptcha);

        return back()->withSuccess(__('ReCaptcha updated successfully'));
    }
}
