<?php

namespace GSoftware\CloudSMS;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use DateTime;

class CloudSMSClient
{
    private string $apiToken;
    private string $senderId;
    private string $baseUrl;
    private Client $client;

    public function __construct(string $apiToken = '', string $senderId = '', string $baseUrl = 'https://cloudsms.gr')
    {
        $this->apiToken = $apiToken;
        $this->senderId = $senderId;
        $this->baseUrl = $baseUrl;
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => "Bearer {$this->apiToken}",
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    /**
     * Get account balance
     * @return array
     */
    public function getBalance(): array
    {
        try {
            $response = $this->client->get('/api/v3/balance');
            $data = json_decode($response->getBody()->getContents(), true);

            if($data['status'] == 'success'){            
                return [
                    'status' => 'success',
                    'data' => str_replace('€', '',  $data['data']['remaining_balance'])
                ];
            }else{
                return [
                    'status' => 'error',
                    'message' => $data['message']
                ];
            }
        } catch (GuzzleException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send an SMS message
     * @param string|array $recipient Single phone number or array of phone numbers
     * @param string $message Message content
     * @param string|null $senderId Optional sender ID
     * @param DateTime|null $scheduleTime Optional schedule time
     * @return array
     */
    public function sendSMS($recipient, string $message, ?string $senderId = null, ?DateTime $scheduleTime = null): array
    {
        try {
            $payload = [
                'recipient' => $recipient,
                'message' => $message,
                'sender_id' => $senderId ?? $this->senderId
            ];

            if ($scheduleTime !== null) {
                $payload['schedule_time'] = $scheduleTime->format('Y-m-d H:i:s');
            }

            $response = $this->client->post('/api/v3/sms/send', [
                'json' => $payload
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'status' => 'success',
                'data' => $data
            ];
        } catch (GuzzleException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send a campaign to contact lists
     * @param array $contactListIds Array of contact list IDs
     * @param string $message Message content
     * @param string|null $senderId Optional sender ID
     * @param DateTime|null $scheduleTime Optional schedule time
     * @return array
     */
    public function sendCampaign(array $contactListIds, string $message, ?string $senderId = null, ?DateTime $scheduleTime = null): array
    {
        try {
            $payload = [
                'contact_list_ids' => $contactListIds,
                'message' => $message,
                'sender_id' => $senderId ?? $this->senderId
            ];

            if ($scheduleTime !== null) {
                $payload['schedule_time'] = $scheduleTime->format('Y-m-d H:i:s');
            }

            $response = $this->client->post('/api/v3/campaign/send', [
                'json' => $payload
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'status' => 'success',
                'data' => $data
            ];
        } catch (GuzzleException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get SMS details
     * @param string $smsId
     * @return array
     */
    public function getSMS(string $smsId): array
    {
        try {
            $response = $this->client->get("/api/v3/sms/{$smsId}");
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'status' => 'success',
                'data' => $data
            ];
        } catch (GuzzleException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get campaign details
     * @param string $campaignId
     * @return array
     */
    public function getCampaign(string $campaignId): array
    {
        try {
            $response = $this->client->get("/api/v3/campaign/{$campaignId}");
            $data = json_decode($response->getBody()->getContents(), true);
            
            return [
                'status' => 'success',
                'data' => $data
            ];
        } catch (GuzzleException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
} 