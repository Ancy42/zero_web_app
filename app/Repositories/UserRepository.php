<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enums\Roles;
use App\Models\Media;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserRepository extends Repository
{
    /**
     * Get the model.
     *
     * @return string The model class.
     */
    public static function model()
    {
        return User::class;
    }

    /**
     * Find a record by phone number.
     *
     * @param  datatype  $phone  description
     * @return Some_Return_Value
     */
    public static function findByPhone($phone)
    {
        return self::query()->where('phone', $phone)->orWhere('email', $phone)->first();
    }

    public static function findByContact($contact)
    {
        return self::query()->where('phone', $contact)
            ->orWhere('email', $contact)
            ->first();
    }

    /**
     * Check if a user with the given social auth provider and email exists in the database.
     * If the user does not exist, create a new user.
     *
     * @param  Request  $request  The request object
     * @param  string  $provider  The social auth provider
     * @return User The found or created user
     */
    public static function socialAuthCheckOrCreate($request, $provider)
    {
        if (! $request['email'] && ! $request['phone']) {
            $user = self::query()->where('auth_type', $provider)->where('auth_id', $request['id'])->first();
            if ($user) {
                return $user;
            }
        }

        $user = self::query()->where('auth_type', $provider)
            ->where('email', $request['email'])
            ->when(! empty($request['phone']), function ($query) use ($request) {
                $query->orWhere('phone', $request['phone']);
            })->first();

        if ($user) {
            return $user;
        }

        $profileUrl = $request['profile_url'];
        $media = null;

        if ($profileUrl) {
            $filename = 'users/'.Str::random(10).'.jpg';

            $imageContent = Http::get($profileUrl)->body();
            Storage::disk('public')->put($filename, $imageContent);

            $media = Media::create([
                'type' => 'image',
                'name' => $filename,
                'src' => $filename,
                'extension' => 'jpg',
            ]);
        }

        $user = self::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'auth_type' => $provider,
            'auth_id' => $request['id'],
            'gender' => $request['gender'],
            'is_active' => true,
            'password' => Hash::make('password'),
            'media_id' => $media ? $media->id : null,
        ]);

        // Create a new customer
        CustomerRepository::storeByRequest($user);

        // create wallet
        WalletRepository::storeByRequest($user);

        $user->assignRole(Roles::CUSTOMER->value);

        return $user;
    }

    /**
     * Register a new user.
     *
     * @param  Request  $request  The request object
     */
    public static function registerNewUser(Request $request): User
    {
        $thumbnail = null;
        if ($request->hasFile('profile_photo')) {
            $thumbnail = MediaRepository::storeByRequest(
                $request->profile_photo,
                'users/profile',
            );
        }

        return self::create([
            'name' => $request->first_name ?? $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth ?? null,
            'country' => $request->country,
            'phone_code' => $request->phone_code,
            'is_active' => true,
        ]);
    }

    public static function storeByRequest($request): User
    {
        $thumbnail = null;
        if ($request->hasFile('profile_photo')) {
            $thumbnail = MediaRepository::storeByRequest(
                $request->profile_photo,
                'users/profile',
                'image'
            );
        }

        return self::create([
            'name' => $request->first_name ?? $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'password' => Hash::make($request->password ?? $request->phone),
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'driving_lience' => $request->driving_lience,
            'date_of_birth' => $request->date_of_birth,
            'vehicle_type' => $request->vehicle_type,
            'country' => $request->country,
            'phone_code' => $request->phone_code,
            'is_active' => $request->is_active ? true : false,
            'shop_id' => $request->shop_id ?? null,
        ]);
    }

    /**
     * Get the access token for the user.
     *
     * @param  User  $user  The user for whom the token is being obtained
     * @return array
     */
    public static function getAccessToken(User $user)
    {
        // $token = $user->createToken('user token');
        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'auth_type' => 'Bearer',
            'token' => $token,
            'expires_at' => now()->addDays(30)->toDateTimeString(),
        ];
    }

    /**
     * Update user by request.
     *
     * @param  $request  The user request
     * @param  mixed  $user  The user
     */
    public static function updateByRequest($request, $user): User
    {
        $thumbnail = self::updateProfilePhoto($request, $user);
        $name = $request->name ?? $request->first_name;
        $user->update([
            'name' => $name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'media_id' => $thumbnail ? $thumbnail->id : null,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth ? Carbon::parse($request->date_of_birth)->format('Y-m-d') : $user->date_of_birth,
            'driving_lience' => $request->driving_lience,
            'vehicle_type' => $request->vehicle_type,
            'country' => $request->country ?? $user->country,
            'phone_code' => $request->phone_code ?? $user->phone_code,
        ]);

        return $user;
    }

    /**
     * Update the user's profile photo.
     */
    private static function updateProfilePhoto($request, $user)
    {
        $thumbnail = $user->media;
        if ($request->hasFile('profile_photo') && $thumbnail == null) {
            $thumbnail = MediaRepository::storeByRequest(
                $request->profile_photo,
                'users/profile',
            );
        }

        if ($request->hasFile('profile_photo') && $thumbnail) {
            $thumbnail = MediaRepository::updateByRequest(
                $request->profile_photo,
                'users/profile',
                'image',
                $thumbnail
            );
        }

        return $thumbnail;
    }
}
