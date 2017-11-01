<?php

namespace Zamoroka\PayPalAllCurrencies\Model\Config;

/**
 * Class PayPalCurrencies
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\Config
 */
class PayPalCurrencies implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Currency codes supported by PayPal methods
     *
     * @var string[]
     */
    protected $supportedCurrencyCodes
        = [
            'AUD',
            'CAD',
            'CZK',
            'DKK',
            'EUR',
            'HKD',
            'HUF',
            'ILS',
            'JPY',
            'MXN',
            'NOK',
            'NZD',
            'PLN',
            'GBP',
            'RUB',
            'SGD',
            'SEK',
            'CHF',
            'TWD',
            'THB',
            'USD',
        ];

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        foreach ($this->supportedCurrencyCodes as $currencyCode) {
            $optionArray[] = ['value' => $currencyCode, 'label' => $currencyCode];
        }

        return $optionArray;
    }
}
