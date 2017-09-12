<?php

namespace Zamoroka\PayPalAllCurrencies\Model\Config;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class ConverterServices
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\Config
 */
class CurrencyServices implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => 'free.currencyconverterapi.com'],
            ['value' => 1, 'label' => 'appnexus.com'],
            ['value' => 2, 'label' => 'finance.google.com'],
        ];
    }
}
