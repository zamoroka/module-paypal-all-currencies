<?php

namespace Zamoroka\PayPalAllCurrencies\Model\Preference\Payment\Cart\SalesModel;

/**
 * Interface SalesModelInterface
 *
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
