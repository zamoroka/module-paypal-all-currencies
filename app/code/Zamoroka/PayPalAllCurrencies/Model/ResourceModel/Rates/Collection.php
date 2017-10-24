<?php

namespace Zamoroka\PayPalAllCurrencies\Model\ResourceModel\Rates;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\ResourceModel\Rates
 */
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Zamoroka\PayPalAllCurrencies\Model\Rates',
            'Zamoroka\PayPalAllCurrencies\Model\ResourceModel\Rates'
        );
    }
}

