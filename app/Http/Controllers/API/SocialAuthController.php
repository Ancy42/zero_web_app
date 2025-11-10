<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialAuthRequest;
use App\Http\Resources\UserResource;
use App\Models\SocialAuth;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SocialAuthController extends Controller
{
    public function login(SocialAuthRequest $request)
    {
        $provider = $request->auth_type;
        $user = UserRepository::socialAuthCheckOrCreate($request, $provider);

        return $this->json('Login successfully', [
            'user' => new UserResource($user),
            'access' => UserRepository::getAccessToken($user),
        ]);

        return view('social-auth.index');
    }

    /**
     * Handle token exchange for a given provider (e.g. Google, Facebook, Twitter).
     *
     * @param  string  $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleTokenExchange($provider, Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $socialAuth = SocialAuth::where('provider', $provider)->first();

        if (! $socialAuth) {
            return $this->json('Invalid provider', [], Response::HTTP_BAD_REQUEST);
        }

        switch ($provider) {
            case 'google':
                return $this->googleTokenExchange($request->code, $socialAuth);
            case 'facebook':
                return $this->facebookTokenExchange($request->code, $socialAuth);
            case 'apple':
                return $this->appleAccessControl($request, $socialAuth);
            default:
                return $this->json('Invalid provider', [], Response::HTTP_BAD_REQUEST);
        }
    }

    private function googleTokenExchange($code, $socialAuth)
    {
        $client = new \Google_Client;
        $client->setClientId($socialAuth->client_id);
        $client->setClientSecret($socialAuth->client_secret);
        $client->setRedirectUri('postmessage');

        // Exchange the authorization code for an access token
        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (array_key_exists('error', $token)) {
            return $this->json('Error: '.$token['error'], [], Response::HTTP_BAD_REQUEST);
        }

        // Retrieve user information with the access token
        $client->setAccessToken($token['access_token']);
        $oauth2 = new \Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        $data = [
            'name' => $userInfo->name,
            'email' => $userInfo->email,
            'phone' => $userInfo->phone ?? null,
            'provider' => 'google',
            'id' => $userInfo->id,
            'profile_url' => $userInfo->picture,
            'gender' => $userInfo->gender,
        ];

        $user = UserRepository::socialAuthCheckOrCreate($data, 'google');

        return $this->json('Login successfully', [
            'user' => new UserResource($user),
            'access' => UserRepository::getAccessToken($user),
        ]);
    }

    private function facebookTokenExchange($code, $socialAuth) {}

    private function appleAccessControl($request, $socialAuth)
    {
        $data = [
            'name' => $request->data['name'] ?? 'Apple User',
            'email' => $request->data['email'] ?? null,
            'phone' => $request->data['phone'] ?? null,
            'provider' => 'apple',
            'id' => $request->data['sub'],
            'profile_url' => null,
            'gender' => null,
        ];

        $user = UserRepository::socialAuthCheckOrCreate($data, 'google');

        return $this->json('Login successfully', [
            'user' => new UserResource($user),
            'access' => UserRepository::getAccessToken($user),
        ]);
    }
}
