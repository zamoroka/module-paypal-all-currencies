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

    /**
     * ConfigPlugin constructor.
     *
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Paypal\Model\Config $config
     * @param bool                         $result
     * @return bool
     */
    public function afterIsCurrencyCodeSupported(Config $config, $result)
    {
        if (!$result && $this->_helper->isModuleEnabled()) {
            $result = true;
        }

        return $result;
    }
}
