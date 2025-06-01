<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\FcmToken;

class FcmService
{
    public function sendNotification($title, $body)
    {
        $tokens = FcmToken::pluck('token')->filter()->values()->toArray(); // danh sách tokens

        if (empty($tokens)) {
            \Log::info('FCM: No tokens found to send message.');
            return;
        }

        $messaging = (new Factory)
            ->withServiceAccount(storage_path(env('FIREBASE_SERVICE_ACCOUNT_PATH')))
            ->createMessaging();

        $notification = Notification::create($title, $body);

        $message = CloudMessage::new()->withNotification($notification);

        try {
            $report = $messaging->sendMulticast($message, $tokens); // Gửi tới nhiều token

            if ($report->hasFailures()) {
                $invalidTokens = $report->invalidTokens();
                FcmToken::whereIn('token', $invalidTokens)->delete();
                \Log::warning('FCM: Removed invalid tokens: ' . implode(', ', $invalidTokens));
            }

            \Log::info("FCM: {$report->successes()->count()} success, {$report->failures()->count()} failed");

        } catch (\Throwable $e) {
            \Log::error('FCM send error: ' . $e->getMessage());
        }
    }
}
