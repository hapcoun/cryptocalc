<?php
namespace app\services;

use app\components\CoinGeckoAdapter;
use app\components\CryptoProviderInterface;
use app\models\Currency;
use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use app\models\Cryptocurrency;
use yii\web\BadRequestHttpException;

class CryptocurrencyService
{
    private CryptoProviderInterface $cryptoProvider;

    public function __construct()
    {
        $this->cryptoProvider = new CoinGeckoAdapter();
    }

    /**
     * @throws Exception
     * @throws \yii\db\Exception
     * @throws InvalidConfigException
     */
    public function updateCryptocurrencies(): void {
        $data = $this->cryptoProvider->getCryptocurrencies();

        if ($data) {
            foreach ($data as $item) {
                $cryptocurrency = Cryptocurrency::findOne(['symbol' => $item['symbol']]) ?? new Cryptocurrency();
                $cryptocurrency->symbol = $item['symbol'];
                $cryptocurrency->name = $item['name'];
                $cryptocurrency->price_usd = $item['price_usd'];

                if (!$cryptocurrency->save()) {
                    Yii::error('Failed to update cryptocurrency: ' . json_encode($cryptocurrency->getErrors()));
                }
            }
        }
    }

    /**
     * Calculate the total price of a cryptocurrency in the specified fiat currency.
     *
     * @param string $symbol The symbol of the cryptocurrency.
     * @param float $amount The amount of cryptocurrency.
     * @param string $currency The code of the fiat currency.
     * @return array The calculation result.
     * @throws BadRequestHttpException
     */
    public function calculate(string $symbol, float $amount, string $currency): array
    {
        $cryptocurrency = Cryptocurrency::findOne(['symbol' => strtoupper($symbol)]);

        if (!$cryptocurrency) {
            throw new BadRequestHttpException("Cryptocurrency with symbol '{$symbol}' not found.");
        }

        $fiatCurrency = Currency::findOne(['code' => strtoupper($currency)]);

        if (!$fiatCurrency) {
            throw new BadRequestHttpException("Fiat currency '{$currency}' not found.");
        }

        $totalPriceInUSD = $cryptocurrency->price_usd * $amount;
        $totalPriceInFiat = round($totalPriceInUSD * $fiatCurrency->rate_to_usd, 2);

        return [
            'amount' => $amount,
            'symbol' => $symbol,
            'price_per_unit' => $cryptocurrency->price_usd,
            'currency' => $currency,
            'total_price' => $totalPriceInFiat,
        ];
    }
}
