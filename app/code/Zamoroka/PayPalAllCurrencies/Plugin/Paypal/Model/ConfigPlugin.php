<?php

namespace Zamoroka\PayPalAllCurrencies\Plugin\Paypal\Model;

use Magento\Paypal\Model\Config;
use Zamoroka\PayPalAllCurrencies\Helper\Data;

/**
 * Class ConfigPlugin
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\Plugin\Paypal
 */
class ConfigPlugin
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $_helper */
    protected $_helper;

    public function __construct(Data $helper)
    {
        $this->_helper = $helper;
    }

    public function afterIsCurrencyCodeSupported(Config $config, $result)
    {
        if (!$result && $this->_helper->isModuleEnabled()) {
            $result = true;
        }

        return $result;
    }
}
