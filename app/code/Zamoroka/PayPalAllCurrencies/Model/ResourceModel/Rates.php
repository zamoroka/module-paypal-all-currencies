<?php

namespace Zamoroka\PayPalAllCurrencies\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Rates
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\ResourceModel
 */
class Rates extends AbstractDb

{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('zamoroka_paypalallcurrencies_rates', 'entity_id');
    }
}
