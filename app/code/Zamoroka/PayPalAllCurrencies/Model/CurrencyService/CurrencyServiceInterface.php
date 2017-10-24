<?php

namespace Zamoroka\PayPalAllCurrencies\Model\CurrencyService;

/**
 * Interface CurrencyService
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\CurrencyService
 */
interface CurrencyServiceInterface
{
    /**
     * @return string
     */
    public function getApiUrl();

    /**
     * Exchange rates
     *
     * @param float $amt
     * @return float
     */
    public function exchange(float $amt);

    /**
     * @return string
     */
    public function getStoreCurrencyCode();

    /**
     * @param null|int $storeId
     * @return string
     */
    public function getPayPalCurrencyCode();

    /**
     * @param string $payPalCurrencyCode
     * @return string
     */
    public function setPayPalCurrencyCode(string $payPalCurrencyCode);
}
