<?php

namespace Zamoroka\PayPalAllCurrencies\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 *
 * @package Zamoroka\PayPalAllCurrencies\Helper
 */
class Data extends AbstractHelper
{
    protected $storeManager;

    protected $objectManager;

    const XML_PATH_GENERAL = 'zamoroka_paypalallcurrencies/general/';

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context      $context
     * @param \Magento\Framework\ObjectManagerInterface  $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
    }

    /**
     * @param string   $field
     * @param null|int $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * @param string   $code
     * @param null|int $storeId
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GENERAL . $code, $storeId);
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return parent::isModuleOutputEnabled('Zamoroka_PayPalAllCurrencies') && $this->getGeneralConfig('enabled');
    }

    /**
     * @param null|int $storeId
     * @return bool
     */
    public function getCurrencyServiceId($storeId = null)
    {
        return $this->getGeneralConfig('currencyservice', $storeId);
    }

    /**
     * @param null|int $storeId
     * @return string
     */
    public function getPayPalCurrency($storeId = null)
    {
        return $this->getGeneralConfig('paypalcurrency', $storeId);
    }

    /**
     * @return array
     */
    public function getPaypalPaymentMethods()
    {
        return [
            'paypal_express',
            'paypal_express_bml'
        ];
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return bool
     */
    public function isOrderPlacedByPaypal(OrderInterface $order)
    {
        return in_array($order->getPayment()->getMethod(), $this->getPaypalPaymentMethods());
    }
}
