<?php

namespace App\Http\Controllers\API\Auth;

use App\Events\SendOTPMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\OTPRequest;
use App\Http\Requests\OTPVerifyRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Models\VerifyManage;
use App\Repositories\UserRepository;
use App\Repositories\VerificationCodeRepository;
use App\Services\SmsGatewayService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    /**
     * Resend OTP to the user's phone.
     *
     * @param  OTPRequest  $request  description
     * @return json description
     */
    public function resendOTP(OTPRequest $request)
    {
        $user = UserRepository::findByPhone($request->phone);

        if (! $user) {
            return $this->json(__('Sorry! No user found with this email/phone.'), [], 422);
        }

        if ($user && $user->is_active) {
            $verifyManage = Cache::rememberForever('verify_manage', function () {
                return VerifyManage::first();
            });

            $type = $request->forgot_password ? $verifyManage?->forgot_otp_type : $verifyManage?->register_otp_type;

            $responseMessage = null;
            $emailOrPhone = null;
            $messageType = $request->forgot_password ? 'Forgot Password' : 'Verification';

            // Create a new verification code
            $verificationCode = VerificationCodeRepository::findOrCreateByContact($user->phone ?? $user->email);
            $OTP = $verificationCode->otp;

            $message = 'Your ' . $messageType . ' OTP is ' . $OTP;

            $phoneCode = null;
            if ($type == 'phone') {

                try {
                    $phoneNumber = $user->phone;
                    $phoneCode = $request->phone_code ?? $user->phone_code;

                    $curl = curl_init();
                    $data = [
                        "key" => "y7SxblQysDYH0gZMyxoRPRMDzz39kekB",
                        "to" => strval($phoneNumber),
                        "type" => "basic",
                        "data" => [
                            "content" => [
                                "plainText" => $message
                            ]
                        ]
                    ];

                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://thesmsbuddy.com/api/v1/sms/send", // ✅ Corrected
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => json_encode($data),
                        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
                        CURLOPT_SSL_VERIFYPEER => false, // Optional, only for localhost
                    ]);

                    $response1 = curl_exec($curl);
                    $curlError = curl_error($curl);
                    curl_close($curl);

                    if ($curlError) {
                        // \Log::error('TheSMSBuddy CURL Error: ' . $curlError);
                        $responseMessage = json_encode(['status' => 'error', 'message' => $curlError]);
                    } else {
                        $decoded = json_decode($response1, true);
                        if (isset($decoded['status']) && $decoded['status'] === 'success') {
                            $responseMessage = 'OTP sent successfully';
                        } else {
                            $responseMessage = $decoded['response'] ?? 'SMS sending failed';
                        }
                    }

                    // dd($response);
                } catch (\Throwable $e) {
                }

                // $responseMessage = json_encode($response1);
                $emailOrPhone = $phoneCode . $user->phone;

                // $response = (new SmsGatewayService)->sendSMS($phoneCode, $phoneNumber, $message);

            } elseif ($user->email) {
                try {
                    SendOTPMail::dispatch($user->email, $message, $OTP);
                } catch (\Throwable $th) {
                }

                $responseMessage = 'Your ' . $messageType . ' code is sent to your email';
                $emailOrPhone = $user->email;
            }

            return $this->json($responseMessage, [
                'email_or_phone' => $emailOrPhone,
                'phone_code' => $phoneCode,
                'otp' => app()->environment('local') ? $OTP : null,
            ]);
        }

        return $this->json('Sorry, your account is not active', [], 422);
    }

    /**
     * Verify the OTP for the user.
     *
     * @param  OTPVerifyRequest  $request  the request containing the OTP to be verified
     */
    public function verifyOtp(OTPVerifyRequest $request)
    {
        $user = UserRepository::findByPhone($request->phone);

        if (! $user) {
            return $this->json('Sorry! No user found', [], 422);
        }

        $verifyManage = Cache::rememberForever('verify_manage', function () {
            return VerifyManage::first();
        });
        $type = $verifyManage?->register_otp_type ?? 'email';

        $verificationCode = VerificationCodeRepository::checkOTP($user->phone ?? $user->email, $request->otp);

        if (! $verificationCode) {
            return $this->json('Invalid otp', [], Response::HTTP_BAD_REQUEST);
        }

        // Mark the user as verified
        if (! $user->email_verified_at && $user->email && $type == 'email') {
            $user->update(['email_verified_at' => now()]);
        } elseif (! $user->phone_verified_at && $user->phone) {
            $user->update(['phone_verified_at' => now()]);
        }

        return $this->json('Otp verified successfully', [
            'token' => $verificationCode->token,
        ]);
    }

    /**
     * Reset the user's password.
     *
     * @param  PasswordResetRequest  $request  The request containing the password reset data
     */
    public function resetPassword(PasswordResetRequest $request)
    {
        $verifyOTP = VerificationCodeRepository::checkByToken($request->token);

        $user = UserRepository::findByPhone($verifyOTP->phone);

        if (! $user) {
            return $this->json('Sorry! No user found with this phone.', [], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $verifyOTP->delete();

        return $this->json('Password reset successfully');
    }

    // public function sendotp($phone, $msg)
    // {
    //     $curl = curl_init();
    //     $data = [
    //         "key" => "y7SxblQysDYH0gZMyxoRPRMDzz39kekB",
    //         "to" => strval($phone),
    //         "type" => "basic",
    //         "data" => [
    //             "content" => [
    //                 "plainText" => $msg
    //             ]
    //         ]
    //     ];
    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => "https://thesmsbuddy.com/api/v1/rcs/send",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => json_encode($data),
    //         CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    //     ]);
    //     $response = curl_exec($curl);
    //     curl_close($curl);
    //     // echo $response;
    //     return $response; // ✅ Do this instead of echo
    // }
}
