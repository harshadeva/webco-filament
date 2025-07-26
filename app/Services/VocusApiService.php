<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VocusApiService
{
    protected string $baseUrl = 'https://extranet.asmorphic.com/api/';
    protected string $token;

    public function __construct()
    {
        $this->login();
    }

    protected function login(): void
    {
        $response = Http::post($this->baseUrl . 'login', [
            'email' => 'project-test@projecttest.com.au',
            'password' => 'oxhyV9NzkZ^02MEB',
        ])->throw()->json();

        if (!isset($response['success']) || $response['success'] !== true) {
            Log::error('Login failed: ', $response);
            throw new \Exception('API_AUTHENTICATION_FAILED');
        }
        $this->token = $response['result']['token'];
    }

    public function fetchApiUniqueNumber(): string
    {
        try {
            $sampleAddressData = [
                'company_id' => 17,
                'street_name' => 'Collins',
                'street_type' => 'Street',
                'street_number' => '254',
                'suburb' => 'Melbourne',
                'postcode' => '3000',
                'state' => 'VIC',
            ];
            $addressResponse = $this->findAddress($sampleAddressData);
            Log::info('Repsonse from API: ', [$addressResponse]);
            $result = $addressResponse['data']['unique_identifier'] ?? '';
            Log::info('Fetched API Unique Identifier: ', [$result]);
            return $result;
        } catch (ConnectionException $e) {
            // return 'TIMEOUT_ERROR';
            return 'SIMULATED_ID_' . uniqid(); // this is only for simulation purposes
        } catch (\Throwable $e) {
            // return 'API_FAIL';
            return 'SIMULATED_ID_' . uniqid(); // this is only for simulation purposes
        }
    }

    public function findAddress(array $addressData): array
    {
        Log::info('Fetched API Unique Identifier: ', $addressData);
        return Http::timeout(10)->withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->baseUrl . 'orders/findaddress', array_merge([
            'company_id' => 17,
        ], $addressData))->throw()->json();
    }

    public function qualifyAddress(string $qualificationIdentifier): array
    {
        return Http::withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->baseUrl . 'orders/qualify', [
            'company_id' => 17,
            'qualification_identifier' => $qualificationIdentifier,
            'service_type_id' => 3,
        ])->throw()->json();
    }
}
