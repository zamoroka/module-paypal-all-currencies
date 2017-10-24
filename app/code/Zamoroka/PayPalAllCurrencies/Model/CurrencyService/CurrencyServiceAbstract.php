<?php

namespace Zamoroka\PayPalAllCurrencies\Model\CurrencyService;

use \Magento\Framework\HTTP\Client\Curl;
use \Magento\Store\Model\StoreManagerInterface;
use \Zamoroka\PayPalAllCurrencies\Helper\Data;

/**
 * Class CurrencyServiceAbstract
 * docs http://www.currencyconverterapi.com/docs
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\CurrencyService
 */
abstract class CurrencyServiceAbstract
{
    /** @var \Magento\Framework\HTTP\Client\Curl $_curl */
    protected $_curl;

    /** @var \Magento\Store\Model\StoreManagerInterface $_storeManager */
    protected $_storeManager;

    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $_helper */
    protected $_helper;

    /** @var  string $_payPalCurrencyCode */
    protected $_payPalCurrencyCode = null;

    /** @var  int $_serviceId */
    protected $_serviceId;

    /**
     * CurrencyServiceAbstract constructor.
     *
     * @param \Magento\Framework\HTTP\Client\Curl        $curl
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data  $helper
     * @param int                                      $serviceId
     */
    public function __construct(
        Curl $curl,
        StoreManagerInterface $storeManager,
        Data $helper,
        $serviceId
    ) {
        $this->_curl = $curl;
        $this->_storeManager = $storeManager;
        $this->_helper = $helper;
        $this->_serviceId = $serviceId;
    }

    /**
     * @return \Magento\Framework\HTTP\Client\Curl
     */
    public function getCurl()
    {
        return $this->_curl;
    }

    /**
     * @return int
     */
    public function getServiceId()
    {
        return $this->_serviceId;
    }

    /**
     * @return string
     */
    public function getStoreCurrencyCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getStoreId();
    }

    /**
     * @return \Zamoroka\PayPalAllCurrencies\Helper\Data
     */
    public function getHelper()
    {
        return $this->_helper;
    }

    /**
     * @param null|int $storeId
     * @return string
     */
    public function getPayPalCurrencyCode()
    {
        if (!$this->_payPalCurrencyCode) {
            $this->_payPalCurrencyCode = $this->getHelper()->getPayPalCurrency($this->getStoreId());
        }

        return $this->_payPalCurrencyCode;
    }

    /**
     * @param string $payPalCurrencyCode
     * @return string
     */
    public function setPayPalCurrencyCode(string $payPalCurrencyCode)
    {
        return $this->_payPalCurrencyCode = $payPalCurrencyCode;
    }
}
