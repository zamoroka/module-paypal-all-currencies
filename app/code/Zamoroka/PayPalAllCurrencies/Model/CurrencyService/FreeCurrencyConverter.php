<?php

namespace Zamoroka\PayPalAllCurrencies\Model\CurrencyService;

/**
 * Class FreeCurrencyConverter
 * docs http://www.currencyconverterapi.com/docs
 * simple GET call  http://free.currencyconverterapi.com/api/v3/convert?q=UAH_USD&compact=ultra
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\CurrencyService
 */
class FreeCurrencyConverter extends CurrencyServiceAbstract implements CurrencyServiceInterface
{
    /**
     * @return string
     */
    public function getApiUrl()
    {
        return 'http://free.currencyconverterapi.com/api/v3/convert';
    }

    /**
     * Exchange rates
     *
     * @param float $amt
     * @return float
     */
    public function exchangeFromService(float $amt)
    {
        $exchangeQuery = $this->getStoreCurrencyCode() . '_' . $this->getPayPalCurrencyCode();
        $url = $this->getApiUrl() . '?' . http_build_query(
                [
                    'q'       => $exchangeQuery,
                    'compact' => 'ultra',
                ]
            );

        $this->getCurl()->get($url);
        $response = json_decode($this->getCurl()->getBody());

        $result = floatval($response->$exchangeQuery) * $amt;

        return round($result, 4);
    }
}
