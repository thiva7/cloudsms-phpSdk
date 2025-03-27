# CloudSMS PHP SDK

A PHP SDK for the CloudSMS API, allowing you to easily integrate SMS functionality into your PHP applications.

## Requirements

- PHP 7.4 or higher
- Composer
- GuzzleHttp 7.0 or higher
- ext-json

## Installation

You can install the package via composer:

```bash
composer require gsoftware/cloudsms-php
```

## Quick Start

```php
use GSoftware\CloudSMS\CloudSMSClient;

// Initialize the client
$client = new CloudSMSClient(
    'your-api-token',
    'your-sender-id'
);

// Check your balance
$balanceResponse = $client->getBalance();
if ($balanceResponse['status'] === 'success') {
    echo "Current balance: " . $balanceResponse['data'];
}

// Send an SMS
$smsResponse = $client->sendSMS(
    '+1234567890',
    'Hello from CloudSMS!'
);
if ($smsResponse['status'] === 'success') {
    echo "SMS sent successfully!";
} else {
    echo "Error: " . $smsResponse['message'];
}
```

## Usage Examples

### Send SMS to Multiple Recipients

```php
$recipients = ['+1234567890', '+9876543210'];
$response = $client->sendSMS(
    $recipients,
    'Hello from CloudSMS!'
);
```

### Schedule an SMS

```php
$scheduleTime = new DateTime('2024-12-20 07:00:00');
$response = $client->sendSMS(
    '+1234567890',
    'This is a scheduled message',
    null, // Use default sender ID
    $scheduleTime
);
```

### Send Campaign to Contact Lists

```php
$contactListIds = ['6415907d0d37a', '6415907d0d7a6'];
$response = $client->sendCampaign(
    $contactListIds,
    'This is a campaign message'
);
```

### Get SMS Details

```php
// Get details of a specific SMS
$smsDetails = $client->getSMS('606812e63f78b');
print_r($smsDetails);
```

### Get Campaign Details

```php
$campaignDetails = $client->getCampaign('campaign_uid_here');
print_r($campaignDetails);
```

## Response Format

All methods return an array with the following structure:

### Success Response
```php
[
    'status' => 'success',
    'data' => 'response data here'
]
```

### Error Response
```php
[
    'status' => 'error',
    'message' => 'A human-readable description of the error'
]
```

## License

This project is licensed under the MIT License.

## Support

For support, please visit [https://cloudsms.gr](https://cloudsms.gr) or contact info@gsoftware.gr. 