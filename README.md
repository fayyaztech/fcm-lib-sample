# fcm-lib-sample

This project includes a `FirebaseService` class for sending notifications via Firebase Cloud Messaging (FCM).

## FirebaseService

The `FirebaseService` class provides functionality to send notifications to devices using Firebase Cloud Messaging (FCM). It uses the Google Client library for authentication and the FCM v1 API for sending messages.

### Installation

To use this project, you need to install the required dependencies. Run the following command:

```bash
composer require google/apiclient
```

### Configuration

Ensure you replace `your-firebase-project-id` in the `FirebaseService.php` file with your actual Firebase project ID. This applies to both the Admin SDK configuration file path and the FCM API URL.

### Firebase Admin SDK Configuration

The `FirebaseService.php` file requires a service account JSON file for authentication. You can download this file from your Firebase Console under the "Service Accounts" section. Replace the placeholder `your-firebase-project-id` in the file path:

```php
$client->setAuthConfig(WRITEPATH . 'uploads/your-firebase-project-id-firebase-adminsdk-fbsvc-6c405be261.json');
```

with the actual Firebase project ID and ensure the file is placed in the specified directory.

### Example File

An example file `example.php` has been added to demonstrate how to use the `FirebaseService` class. You can run it as follows:

```bash
php example.php
```

### Example Usage

```php
use App\Libraries\FirebaseService;

// Initialize the FirebaseService
$firebase = new FirebaseService();

// Send a notification
$response = $firebase->sendNotification(
    'Test Title', // Notification title
    'Test Message', // Notification body
    'fqOzNBB...IdvA', // Firebase device token
    [
        'type' => 'test', // Custom data type
        'data' => ['key' => 'value'] // Custom data payload
    ],
    'https://example.com/image.png' // Optional image URL
);

// Log or handle the response
print_r($response);
```

### Methods

#### `sendNotification`

**Parameters:**
- `string $title`: Title of the notification.
- `string $message`: Body of the notification.
- `string $token`: Firebase device token to send the notification to.
- `array $data`: Custom data payload containing `type` and `data` fields (optional).
- `string|null $imageUrl`: URL of the image to include in the notification (optional).

**Returns:**
- `mixed`: Response from Firebase or an error message.

**Example:**
```php
$firebase->sendNotification(
    'Hello World',
    'This is a test notification.',
    'your-device-token',
    ['type' => 'alert', 'data' => ['foo' => 'bar']],
    null
);
```