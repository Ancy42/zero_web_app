<?php

namespace App\Http\Controllers\API\Rider;

use App\Enums\Roles;
use App\Models\User;
use App\Models\Driver;
use App\Models\DeviceKey;
use App\Events\SendOTPMail;
use App\Models\VerifyManage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RiderRequest;
use App\Services\SmsGatewayService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Repositories\DriverRepository;
use App\Http\Resources\RiderUserResource;
use App\Repositories\DeviceKeyRepository;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreatePasswordRequest;
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
    public function login(LoginRequest $request)
    {
        if ($user = $this->authenticate($request)) {
            if (! $user->is_active) {
                return $this->json('Your account is not active. please contact the admin', [], Response::HTTP_BAD_REQUEST);
            }

            if ($request->device_key && ! $this->findByKey($request->device_key, $user)) {
                DeviceKey::create([
                    'user_id' => $user->id,
                    'key' => $request->device_key,
                    'device_type' => $request->device_type ?? 'android',
                ]);
            }

            return $this->json('Log In Successful', [
                'user' => new UserResource($user),
                'access' => UserRepository::getAccessToken($user),
            ]);
        }

        return $this->json('Credential is invalid!', [], Response::HTTP_BAD_REQUEST);
    }

    /**
     * register new user.
     *
     * @param  RiderRequest  $request  The request object containing the user data.
     * @return \Illuminate\Http\Response
     */
    public function register(RiderRequest $request)
    {
        $user = UserRepository::storeByRequest($request);
        $user->assignRole(Roles::DRIVER->value);

        DriverRepository::storeByUser($user);

        $user->update(['is_active' => false]);

        $verificationCode = VerificationCodeRepository::findOrCreateByContact($request->phone ?? $request->email);

        $message = 'Hello Your Ridder Registration OTP is '.$verificationCode->otp;
        $phoneCode = $request->phone_code;
        try {
            (new SmsGatewayService)->sendSMS($phoneCode, $request->phone, $message);
        } catch (\Throwable $th) {
            // throw $th;
        }

        return $this->json('Your profile is under review', [
            'user' => RiderUserResource::make($user),
        ]);
    }

    /**
     * Authenticate a user.
     */
    private function authenticate($request)
    {
        $user = UserRepository::findByContact($request->phone);
        if (! is_null($user) && $user->driver) {
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
        $user = User::find(auth()->user()->id);
        $currentPassword = $request->current_password;
        if (Hash::check($currentPassword, $user->password)) {

            if (Hash::check($request->password, $user->password)) {
                return $this->json('New password cannot be same as current password', [], Response::HTTP_BAD_REQUEST);
            }

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->json('Password change successfully', [
                'user' => RiderUserResource::make($user),
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
    public function sendOTP(Request $request)
    {
        $verifyManage = Cache::rememberForever('verify_manage', function () {
            return VerifyManage::first();
        });

        $forgotPassword = $request->forgot_password;

        if ($forgotPassword) {
            $validate = 'required|numeric|exists:users,phone';
            $request->validate(['phone' => $validate]);
        }

        $type = $forgotPassword ? $verifyManage?->forgot_otp_type : $verifyManage?->register_otp_type;

        $responseMessage = null;
        $emailOrPhone = null;

        $user = UserRepository::findByPhone($request->phone ?? $request->email);

        $verificationCode = VerificationCodeRepository::findOrCreateByContact($request->phone ?? $request->email);
        $OTP = $verificationCode?->otp;

        $message = 'Your Ridder '.($forgotPassword ? 'Forgot Password' : 'Registration').' OTP is '.$OTP;

        if ($type == 'phone') {

            try {
                $phoneNumber = $request->phone;
                $phoneCode = $request->phone_code;

                $response = (new SmsGatewayService)->sendSMS($phoneCode, $phoneNumber, $message);

                // dd($response);
            } catch (\Throwable $th) {
                // throw $th;
            }

            $responseMessage = 'Please Verify your phone number. We sent otp to your phone number';
            $emailOrPhone = $phoneCode.$request->phone;
        } else {

            $email = $user?->email ?? $request->phone;

            try {
                SendOTPMail::dispatch($email, $message, $OTP);
            } catch (\Throwable $th) {
            }

            $responseMessage = 'We sent otp to your email address';
            $emailOrPhone = $email;
        }

        return $this->json($responseMessage, [
            'email_or_phone' => $emailOrPhone,
            'otp' => app()->environment('local') ? $OTP : null,
        ]);
    }

    /**
     * verify OTP
     *
     * @param  Request  $request  The request object containing the user data.
     */
    public function verifyOtp(Request $request)
    {
        $verificationCode = VerificationCodeRepository::checkOTP($request->phone, $request->otp);

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
    public function createPassword(CreatePasswordRequest $request)
    {
        $verifyOtp = VerificationCodeRepository::checkByToken($request->token);
        $user = UserRepository::query()->where('phone', $verifyOtp->phone)->first();

        if (! $user) {
            return $this->json('User not found', [], Response::HTTP_NOT_FOUND);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $verifyOtp->delete();

        return $this->json('Password created successfully', [
            'user' => RiderUserResource::make($user),
        ]);
    }

    public function deleteAccountRider(){
        $user=auth()->user();

        if (app()->environment() == 'local' && $user->email == 'rider@readygrocery.com') {
            return $this->json('You can not delete the driver in demo mode!');
        }
        
        $driver=Driver::where('user_id',$user->id)->first();

        if($driver){
           $driver->delete();
        }
        $user->delete();
        return $this->json('Delete Account Successfully!');
    }
}
