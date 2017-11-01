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
     * Exchange rates from api service
     *
     * @param float $amt
     *
     * @return float
     */
    public function exchangeFromService(float $amt);

    /**
     * Exchange rates from database
     *
     * @param float $amt
     * @param int   $precision
     *
     * @return float
     */
    public function exchange($amt, $precision = 4);

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
