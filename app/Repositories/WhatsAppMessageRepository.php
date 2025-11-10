<?php
namespace App\Repositories;

use App\Models\SMSConfig;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppMessage;
use App\Services\TwilioService;
use App\Events\WhatsAppChatEvent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Abedin\Maker\Repositories\Repository;

class WhatsAppMessageRepository extends Repository
{
    const MEDIA_PHOTO_PATH = 'whatsapp/media/';
    public static function model()
    {
        return WhatsAppMessage::class;
    }


    public static function addOrUpdateContact(string $phoneNumber,  $profileName = null)
    {

        $contact = WhatsAppContact::where('phone_number', $phoneNumber)->first();

        if (!$contact) {
            $contact = WhatsAppContact::create([
                'phone_number' => $phoneNumber,
                'profile_name' => $profileName,
                'last_message_at' => now()
            ]);
        } else {
            if ($profileName && $contact->profile_name !== $profileName) {
                $contact->update([
                    'profile_name' => $profileName,
                ]);
            }
        }

        return $contact;
    }

    protected static function createMediaFile(UploadedFile $mediaFile)
    {
        return Storage::disk('public')->put('/' . trim(self::MEDIA_PHOTO_PATH, '/'), $mediaFile);
    }


    public static function incomingMessage($request)
    {
        $fromNumber = $request->WaId;
        $profileName = $request->ProfileName ?? null;
        $body = $request->Body ?? '';

        $contact = self::addOrUpdateContact($fromNumber, $profileName);

        $message = self::create([
            'contact_id' => $contact->id,
            'direction' => 'inbound',
            'message_type' => $request->MessageType ?? 'text',
            'body' => $body,
            'media_urls' => $request->MediaUrl0 ?? '',
            'status' => $request->SmsStatus ?? 'received',
        ]);


        if ($request->MessageType !== 'text' && $request->NumMedia > 0) {
            $mediaUrl = $request->MediaUrl0;

            $response = Http::withBasicAuth(
                config('services.twilio.sid'),
                config('services.twilio.token')
            )->get($mediaUrl);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type');
                $extension = explode('/', $contentType)[1];
                $filename = 'whatsapp_media_' . now()->timestamp . '.' . $extension;

                Storage::disk('public')->put("whatsapp_media/{$filename}", $response->body());

                $message->update([
                    'media_urls' => "whatsapp_media/{$filename}",
                ]);
            }
        }

        $contact->update(['last_message_at' => now()]);
        broadcast(new WhatsAppChatEvent($fromNumber, $message));

        return $message;
    }


    public static function sendMessage($request)
    {
        $phone =$request->phone_number;

        $twilioConfig = SMSConfig::where('provider', 'twilio')->first();
        $data = $twilioConfig ? json_decode($twilioConfig->data, true) : null;

        if (
            $twilioConfig &&
            $twilioConfig->status == 1 &&
            !empty($data['twilio_sid']) &&
            !empty($data['twilio_token']) &&
            !empty($data['twilio_from'])
        ) {
            try {
                $twilioService = new TwilioService($data);
                $twilioService->sendWhatsAppText($phone, $request->message);
            } catch (\Exception $e) {
            }
        }

        $contact = self::addOrUpdateContact($phone);

        $message = self::create([
            'contact_id' => $contact->id,
            'direction' => 'outbound',
            'message_type' =>'text',
            'body' => $request->message,
            'status' => 'delivered',
        ]);

        $contact->update(['last_message_at' => now()]);

        return $message;
    }
}
