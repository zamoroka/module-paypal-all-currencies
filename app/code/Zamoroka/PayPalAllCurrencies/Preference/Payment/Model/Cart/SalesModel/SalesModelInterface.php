<?php

namespace Zamoroka\PayPalAllCurrencies\Preference\Payment\Model\Cart\SalesModel;

/**
 * Interface SalesModelInterface
 *
 * @method float setPaypalSubtotal(float $amt)
 * @method float setPaypalGrandTotal(float $amt)
 * @method float setPaypalShippingAmount(float $amt)
 * @method float setPaypalDiscountAmount(float $amt)
 * @method float setPaypalTaxAmount(float $amt)
 * @method float setPaypalRate(float $amt)
 * @method string setPaypalCurrencyCode(string $code)
 * @package Zamoroka\PayPalAllCurrencies\Model\Preference\Payment\Cart\SalesModel
 */
interface SalesModelInterface
{
    /**
     * @return float
     */
    public function getPaypalSubtotal();

    /**
     * @return float
     */
    public function getPaypalGrandTotal();

    /**
     * @return float
     */
    public function getPaypalShippingAmount();

    /**
     * @return float
     */
    public function getPaypalDiscountAmount();

    /**
     * @return float
     */
    public function getPaypalTaxAmount();

    /**
     * @return float
     */
    public function getPaypalRate();

    /**
     * @return string
     */
    public function getPaypalCurrencyCode();
}
