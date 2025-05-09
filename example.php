<?php

require 'vendor/autoload.php';

use App\Libraries\FirebaseService;

// Example usage of FirebaseService
$firebase = new FirebaseService();
$response = $firebase->sendNotification(
    'Example Title',
    'This is an example notification message.',
    'example_device_token', // Replace with a valid Firebase device token
    [
        'type' => 'example',
        'data' => ['key' => 'value']
    ],
    'https://example.com/image.png' // Optional image URL
);

print_r($response);
