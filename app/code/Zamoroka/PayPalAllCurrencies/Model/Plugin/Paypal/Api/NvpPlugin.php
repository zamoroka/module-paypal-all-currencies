<?php

namespace Zamoroka\PayPalAllCurrencies\Model\Plugin\Paypal\Api;

use Magento\Paypal\Model\Api\Nvp;
use Zamoroka\PayPalAllCurrencies\Helper\Data;

/**
 * Class NvpPlugin
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\Plugin\Paypal\Api
 */
class NvpPlugin
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $_helper */
    protected $_helper;

    /**
     * NvpPlugin constructor.
     *
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * TODO-zamoroka: remove this plugin. Find another solution
     * used in Magento\Paypal\Model\Express\Checkout function start()
     * @param \Magento\Paypal\Model\Api\Nvp $nvp
     * @param                               $key
     * @param null                          $value
     * @return array
     */
    public function beforeSetData(Nvp $nvp, $key, $value = null)
    {
        if ($this->_helper->isModuleEnabled()) {
            switch ($key) {
                case 'amount':
                    $value = $this->_helper->convertToPaypalCurrency($value);
                    break;
                case 'currency_code':
                    $value = 'USD';
                    break;
                default:
                    break;
            }
        }

        return [$key, $value];
    }
}
