<?php
namespace app\components;

interface CryptoProviderInterface
{
    public function getCryptocurrencies(): array;
}