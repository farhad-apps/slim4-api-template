<?php

namespace App\Libraries;

class UsdtFetcherPrice
{
    private HttpClient $httpClient;
    private string $apiUrl = 'https://api.coingecko.com/api/v3';

    public function __construct()
    {
        $this->httpClient = new HttpClient($this->apiUrl);
    }

    public function getCurrentPrice(string $currency = 'usd'): array
    {
        try {
            $response = $this->httpClient->get('/simple/price', [
                'ids' => 'tether',
                'vs_currencies' => $currency,
                'include_market_cap' => 'true',
                'include_24hr_vol' => 'true',
            ]);

            if (isset($response['error'])) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch USDT price',
                    'data' => null,
                ];
            }

            if (!isset($response['tether'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid API response',
                    'data' => null,
                ];
            }

            $data = $response['tether'];
            $currencyKey = strtolower($currency);

            return [
                'success' => true,
                'message' => 'USDT price fetched successfully',
                'data' => [
                    'symbol' => 'USDT',
                    'currency' => strtoupper($currency),
                    'price' => $data[$currencyKey] ?? null,
                    'market_cap' => $data['usd_market_cap'] ?? null,
                    'volume_24h' => $data['usd_24h_vol'] ?? null,
                    'timestamp' => date('Y-m-d H:i:s'),
                ],
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Error fetching USDT price: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function getPriceInMultipleCurrencies(array $currencies = ['usd', 'eur', 'gbp']): array
    {
        try {
            $response = $this->httpClient->get('/simple/price', [
                'ids' => 'tether',
                'vs_currencies' => implode(',', $currencies),
            ]);

            if (isset($response['error']) || !isset($response['tether'])) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch USDT prices',
                    'data' => null,
                ];
            }

            $data = $response['tether'];
            $prices = [];

            foreach ($currencies as $currency) {
                $currencyKey = strtolower($currency);
                if (isset($data[$currencyKey])) {
                    $prices[] = [
                        'currency' => strtoupper($currency),
                        'price' => $data[$currencyKey],
                    ];
                }
            }

            return [
                'success' => true,
                'message' => 'USDT prices fetched successfully',
                'data' => [
                    'symbol' => 'USDT',
                    'prices' => $prices,
                    'timestamp' => date('Y-m-d H:i:s'),
                ],
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Error fetching USDT prices: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function isUsdtStable(string $currency = 'usd', float $tolerance = 0.02): bool
    {
        $result = $this->getCurrentPrice($currency);

        if (!$result['success']) {
            return false;
        }

        $price = $result['data']['price'];
        $expectedPrice = 1.0;

        return abs($price - $expectedPrice) <= $tolerance;
    }
}
