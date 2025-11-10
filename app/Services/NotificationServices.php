<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class NotificationServices
{
    public static function sendNotification(string $body, array $tokens, $title = null)
    {
        $notification = Notification::create($title, $body);

        $firebaseCredentials = storage_path('app/public/firebase_credentials.json');

        if (! file_exists($firebaseCredentials)) {
            return [
                'success' => false,
                'message' => 'Firebase notification can not be sent. Please provide valid firebase credentials file.',
            ];
        }

        $messaging = (new Factory)->withServiceAccount($firebaseCredentials)->createMessaging();

        $message = CloudMessage::new()->withNotification($notification);

        try {
            $messaging->sendMulticast($message, $tokens);

            return [
                'success' => true,
                'message' => 'Notification sent successfully',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
