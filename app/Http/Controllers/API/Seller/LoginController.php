<?php

namespace App\Http\Controllers\API\Seller;

use App\Models\Shop;
use App\Models\DeviceKey;
use App\Events\SendOTPMail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Repositories\ShopRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Events\AdminProductRequestEvent;
use App\Http\Requests\ShopCreateRequest;
use App\Http\Requests\SellerLoginRequest;
use App\Repositories\DeviceKeyRepository;
use App\Http\Requests\EmailSendOTPRequest;
use App\Http\Resources\SellerUserResource;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreatePasswordRequest;
use App\Repositories\NotificationRepository;
use App\Http\Requests\CheckEmailPhoneRequest;
use App\Repositories\VerificationCodeRepository;

class LoginController extends Controller
{
    /**
     * check user status
     */
    public function checkUserStatus(Request $request)
    {
        $request->validate(['phone' => 'nullable|numeric|exists:users,phone']);

        $user = UserRepository::findByContact($request->phone);

        $message = $user?->is_active ? 'Your account is active' : 'Your account is not active. please contact the admin';

        return $this->json($message, [
            'user_status' => (bool) $user?->is_active ?? false,
        ]);
    }

    /**
     * login a user.
     */
    public function login(SellerLoginRequest $request)
    {
        if ($user = $this->authenticate($request)) {
            if (! $user->is_active) {
                return $this->json('Your account is not active. please contact the admin', [], Response::HTTP_BAD_REQUEST);
            }

            if ($request->device_key) {
                DeviceKeyRepository::storeByRequest($user, $request);
            }

            return $this->json('Log In Successful', [
                'user' => SellerUserResource::make($user),
                'access' => UserRepository::getAccessToken($user),
            ]);
        }

        return $this->json('Credential is invalid!', [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * register new user.
     *
     * @param  ShopCreateRequest  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function register(ShopCreateRequest $request)
    {
        $shop = ShopRepository::storeByRequest($request);

        $shop->user()->update(['is_active' => false]);

        $message = 'New Shop Created Request';
        try {
            AdminProductRequestEvent::dispatch($message);
        } catch (\Throwable $th) {
        }

        $data = (object) [
            'title' => $message,
            'content' => 'New Shop created for review. shop name: '.$shop->name,
            'url' => '/admin/shops/'.$shop->id,
            'icon' => 'bi-shop',
            'type' => 'success',
        ];
        // store notification
        NotificationRepository::storeByRequest($data);

        return $this->json('Your profile is under review', [
            'user' => SellerUserResource::make($shop->user),
        ]);
    }

    /**
     * Authenticate a user.
     */
    private function authenticate($request)
    {
        $user = UserRepository::findByContact($request->contact);
        if (! is_null($user) && $user->shop) {
            if (Hash::check($request->password, $user->password)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Find a user by device key.
     */
    public function findByKey($key, $user)
    {
        return DeviceKey::where('key', $key)->where('user_id', $user->id)->first();
    }

    /**
     * change password.
     *
     * @param  ChangePasswordRequest  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $currentPassword = $request->current_password;
        if (Hash::check($currentPassword, $user->password)) {

            if (Hash::check($request->password, $user->password)) {
                return $this->json('New password cannot be same as current password', [], Response::HTTP_BAD_REQUEST);
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->json('Password change successfully', [
                'user' => SellerUserResource::make($user),
            ]);
        }

        return $this->json('Current password is incorrect', [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * logout a user.
     *
     * @param  Request  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        /** @var \User $user */
        $user = auth()->user();

        if ($request->device_key) {
            DeviceKeyRepository::deleteByKey($request->device_key, $user);
        }

        if ($user) {
            $user->currentAccessToken()->delete();

            return $this->json('Logged out successfully!');
        }

        return $this->json('No Logged in user found', [], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * send OTP
     *
     * @param  Request  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function sendOTP(EmailSendOTPRequest $request)
    {
        $verificationCode = VerificationCodeRepository::findOrCreateByContact($request->email);

        $type = $request->forgot_password ? 'Forgot Password' : 'Registration';
        $otp = $verificationCode->otp;

        $message = 'Hello Your Seller '.$type.' OTP is '.$otp;

        try {
            SendOTPMail::dispatch($request->email, $message, $otp);
        } catch (\Throwable $th) {
            // throw $th;
        }

        return $this->json('Please Verify your email. We sent otp to your email address', [
            'otp' => app()->environment('local') ? $otp : null,
        ]);
    }

    /**
     * verify OTP
     *
     * @param  Request  $request  The request object containing the user data.
     */
    public function verifyOtp(Request $request)
    {
        $verificationCode = VerificationCodeRepository::checkOTP($request->email, $request->otp);

        if (! $verificationCode) {
            return $this->json('Invalid OTP', [], Response::HTTP_BAD_REQUEST);
        }

        return $this->json('Otp matched successfully!', [
            'token' => $verificationCode->token,
        ]);
    }

    /**
     * create new password
     *
     * @param  CreatePasswordRequest  $request  The request object containing the user data.
     */
    public function forgotPassword(CreatePasswordRequest $request)
    {
        $verifyOtp = VerificationCodeRepository::checkByToken($request->token);
        $user = UserRepository::query()->where('phone', $verifyOtp->phone)->orWhere('email', $verifyOtp->phone)->first();

        if (! $user) {
            return $this->json('User not found', [], Response::HTTP_NOT_FOUND);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $verifyOtp->delete();

        return $this->json('Password changes successfully', [
            'user' => SellerUserResource::make($user),
        ]);
    }

    public function checkEmailPhone(CheckEmailPhoneRequest $request)
    {
        return $this->json(__('Email and phone number are available'));
    }

    public function deleteAccountSeller(){

        $user=auth()->user();
        if (app()->environment() == 'local' && $user->email == 'shop@readygrocery.com') {
            return $this->json('You can not delete the shop in demo mode!');
        }
       
        $shop=Shop::where('user_id',$user->id)->first();
        if($shop){
           $shop->delete();
        }
        $user->delete();
        return $this->json('Delete Account Successfully!');
    }
}
