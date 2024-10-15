<?php
namespace app\components;

use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class CoinGeckoAdapter implements CryptoProviderInterface
{
    private string $apiUrl = 'https://api.coingecko.com/api/v3/coins/markets';

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getCryptocurrencies(): array
    {
        $client = new Client();
        $params = [
            'vs_currency' => 'usd',
            'order' => 'market_cap_desc',
            'sparkline' => false,
        ];
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($this->apiUrl)
            ->setData($params)
            ->addHeaders(['User-Agent' => 'MyApp/1.0'])
            ->send();
        $result = [];

        if ($response->isOk) {
            $data = $response->data ?? [];

            foreach ($data as $item) {
                $result[] = [
                    'symbol' => strtoupper($item['symbol']),
                    'name' => $item['name'],
                    'price_usd' => $item['current_price'],
                ];
            }
        } else {
            throw new Exception('Unable to fetch cryptocurrency data.', $response->statusCode);
        }

        return $result;
    }
}