<?php

namespace Zamoroka\PayPalAllCurrencies\Model\CurrencyService;

/**
 * Class ApiAppnexus
 * docs https://wiki.appnexus.com/display/api/Currency+Service
 * simple call https://api.appnexus.com/currency?code=USD&show_rate=true
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\CurrencyService
 */
class ApiAppnexus extends CurrencyServiceAbstract implements CurrencyServiceInterface
{
    /**
     * @return string
     */
    public function getApiUrl()
    {
        return 'https://api.appnexus.com/currency';
    }

    /**
     * Exchange rates
     *
     * @param float $amt
     * @return float
     */
    public function exchangeFromService(float $amt)
    {
        $currencyRate = $this->getUSDRate($this->getPayPalCurrencyCode($this->getStoreId()))
            / $this->getUSDRate($this->getStoreCurrencyCode());

        return round($currencyRate * $amt, 4);
    }

    /**
     * @param string $code
     * @return null
     */
    protected function getUSDRate(string $code)
    {
        $url = $this->getApiUrl() . '?' . http_build_query(
                [
                    'code'      => $code,
                    'show_rate' => 'true',
                ]
            );

        $this->getCurl()->get($url);
        $response = json_decode($this->getCurl()->getBody());

        if ($response->response->status === 'OK') {
            return $response->response->currency->rate_per_usd;
        }

        return null;
    }
}
