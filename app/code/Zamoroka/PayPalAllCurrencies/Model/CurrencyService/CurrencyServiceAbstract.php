<?php

namespace Zamoroka\PayPalAllCurrencies\Model\CurrencyService;

use \Magento\Framework\HTTP\Client\Curl;
use \Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use \Zamoroka\PayPalAllCurrencies\Helper\Data;
use \Zamoroka\PayPalAllCurrencies\Model\RatesFactory;

/**
 * Class CurrencyServiceAbstract
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\CurrencyService
 */
abstract class CurrencyServiceAbstract
{
    /** @var \Magento\Framework\HTTP\Client\Curl $_curl */
    protected $curl;

    /** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
    protected $storeManager;

    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $helper */
    protected $helper;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\RatesFactory $ratesFactory */
    protected $ratesFactory;

    /** @var  string $payPalCurrencyCode */
    protected $payPalCurrencyCode = null;

    /** @var  int $_serviceId */
    protected $serviceId;

    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;

    /**
     * CurrencyServiceAbstract constructor.
     *
     * @param \Magento\Framework\HTTP\Client\Curl              $curl
     * @param \Magento\Store\Model\StoreManagerInterface       $storeManager
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data        $helper
     * @param \Zamoroka\PayPalAllCurrencies\Model\RatesFactory $ratesFactory
     * @param \Psr\Log\LoggerInterface                         $logger
     * @param int                                              $serviceId
     */
    public function __construct(
        Curl $curl,
        StoreManagerInterface $storeManager,
        Data $helper,
        RatesFactory $ratesFactory,
        LoggerInterface $logger,
        $serviceId
    ) {
        $this->curl = $curl;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
        $this->ratesFactory = $ratesFactory;
        $this->logger = $logger;
        $this->serviceId = $serviceId;
    }

    /**
     * @return \Magento\Framework\HTTP\Client\Curl
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @return int
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * @return string
     */
    public function getStoreCurrencyCode()
    {
        return $this->storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getStoreId();
    }

    /**
     * @return \Zamoroka\PayPalAllCurrencies\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return string
     */
    public function getPayPalCurrencyCode()
    {
        if (!$this->payPalCurrencyCode) {
            $this->payPalCurrencyCode = $this->getHelper()->getPayPalCurrency($this->getStoreId());
        }

        return $this->payPalCurrencyCode;
    }

    /**
     * @param string $payPalCurrencyCode
     * @return string
     */
    public function setPayPalCurrencyCode(string $payPalCurrencyCode)
    {
        return $this->payPalCurrencyCode = $payPalCurrencyCode;
    }

    /**
     * Exchange rates from database
     *
     * @param float $amt
     * @param int   $precision
     * @return float
     */
    public function exchange($amt, $precision = 4)
    {
        $exchanged = 0;
        try {
            /** @var \Zamoroka\PayPalAllCurrencies\Model\Rates $ratesModel */
            $ratesModel = $this->ratesFactory->create();
            $rate = $ratesModel->getExistingRate(
                $this->getServiceId(), $this->getStoreCurrencyCode(), $this->getPayPalCurrencyCode()
            );
            $exchanged = round($amt * $rate->getRate(), $precision);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $exchanged;
    }
}
