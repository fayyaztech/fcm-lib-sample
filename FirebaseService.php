<?php

namespace App\Libraries;

use Google\Client;

class FirebaseService
{
    private $token;

    public function __construct()
    {
        // Initialize the Google Client for Firebase
        $client = new Client();
        $client->setAuthConfig(WRITEPATH . 'uploads/your-firebase-project-id-firebase-adminsdk-fbsvc-6c405be261.json');
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials();
        $this->token = $client->fetchAccessTokenWithAssertion();
    }

    /**
     * Send Notification to Multiple Devices via Firebase Cloud Messaging (FCM)
     *
     * @param string $title Title of the notification
     * @param string $message Body of the notification
     * @param array $tokens List of Firebase device tokens to send notification to
     * @param array $data Array containing 'type' and 'data' fields
     * @param string|null $imageUrl URL of the image to include in the notification (optional)
     *
     * @return mixed Response from Firebase or error message
     * 
     * @example
     * $firebase = new FirebaseService();
     * $firebase->sendNotification(
     *     'Test Title',
     *     'Test Message',
     *     ['fqOzNBB...IdvA'],  // Firebase device token
     *     [
     *         'type' => 'test',
     *         'data' => ['key' => 'value']
     *     ]
     * );
     */
    public function sendNotification($title, $message, $token, $data = [], $imageUrl = null)
    {
        // Initialize cURL
        $curl = curl_init();
        $responses = [];

        // foreach ($tokens as $token) {
        // Message data to send - FCM v1 API format
        $messageData = [
            'message' => [
                'token' => $token, //whom you want to send firebase token of device
                'notification' => [
                    'title' => $title,
                    'body' => $message
                ],
                'android' => [
                    'notification' => [
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                    ]
                ],
                'data' => [
                    'type' => strval($data['type'] ?? ''),
                    'data' => json_encode($data['data'] ?? [])
                ]
            ]
        ];

        if ($imageUrl) {
            $messageData['message']['notification']['image'] = $imageUrl;
        }

        // Set cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://fcm.googleapis.com/v1/projects/your-firebase-project-id/messages:send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($messageData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token['access_token']
            ]
        ]);

        // Execute cURL request
        $response = curl_exec($curl);
        $responses[] = $response;

        // Log the actual request payload and response for debugging
        log_message('debug', 'FCM Request Payload: ' . json_encode($messageData));
        log_message('info', 'FCM Response for token ' . $token . ': ' . $response);

        // Check for cURL errors
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            log_message('error', 'FCM cURL Error: ' . $error_msg);
        }
        // }

        curl_close($curl);
        return $responses;
    }
}
