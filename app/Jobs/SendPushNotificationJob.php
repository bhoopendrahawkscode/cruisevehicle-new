<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\CommonService;
use DB;
use Cache;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userData;
    protected $notificationData;

    public function __construct($userData, $notificationData = array())
    {
        $this->userData = $userData;
        $this->notificationData = $notificationData;
    }

    public function handle(): void
    {
        try {
            if (!empty($this->notificationData)) {
                DB::table('user_notifications')->insert($this->notificationData);
            }

            if ($this->userData['notification_status'] == 0) {
                return;
            }
            Cache::forget('get_notification_count_'.$this->userData['id'].'');

            if ($this->userData['device_token']) {

                $serviceAccountKey = json_decode(file_get_contents(base_path().DIRECTORY_SEPARATOR."fcm.json"), true);



                $url = "https://fcm.googleapis.com/v1/projects/{$serviceAccountKey['project_id']}/messages:send";

                $nowSeconds = time();
                $expiresSeconds = $nowSeconds + (60 * 60); // Token valid for 1 hour

                $payload = array(
                    "iss" => $serviceAccountKey['client_email'],
                    "sub" => $serviceAccountKey['client_email'],
                    "aud" => "https://fcm.googleapis.com/",
                    "iat" => $nowSeconds,
                    "exp" => $expiresSeconds,
                    "scope" => "https://www.googleapis.com/auth/firebase.messaging"
                );

                $jwt = JWT::encode($payload, $serviceAccountKey['private_key'], 'RS256');
				$notification = [];
                $notification = [
                    'message' => [
                        'token' => $this->userData['device_token'],
                        'notification' => [
                            'title' => $this->notificationData['title'],
                            'body' => $this->notificationData['description'],

                        ],
                        'data' => [
                            'jsonKey' => json_encode($this->notificationData)
                        ]
                    ]
                ];
                $headers = [
                    'Authorization: Bearer ' . $jwt,
                    'Content-Type: application/json'
                ];

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_exec($ch);
                curl_error($ch);
                curl_close($ch);
            }

        } catch (\Exception $e) {
            CommonService::createExceptionLog($e);
        }
    }
}
