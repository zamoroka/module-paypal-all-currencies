<?php

namespace Zamoroka\PayPalAllCurrencies\Model;

use Magento\Framework\App\Action\Context;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface;

/**
 * Class CurrencyServiceFactory
 *
 * @package Zamoroka\PayPalAllCurrencies\Model
 */
class CurrencyServiceFactory
{
    /**
     * Here you can register new currency api service
     * class must implement CurrencyServiceInterface
     *
     * @var array $_services
     */
    protected $_services
        = [
            0 => [
                'className' => 'FreeCurrencyConverter',
                'label'     => 'free.currencyconverterapi.com'
            ],
            1 => [
                'className' => 'ApiAppnexus',
                'label'     => 'appnexus.com'
            ],
            2 => [
                'className' => 'FinanceGoogle',
                'label'     => 'finance.google.com'
            ]
        ];

    /** @var \Magento\Framework\ObjectManagerInterface $_objectManager */
    protected $_objectManager;

    /**
     * CurrencyConverter constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(Context $context)
    {
        $this->_objectManager = $context->getObjectManager();
    }

    /**
     * @param int $serviceId
     * @return false|CurrencyServiceInterface
     */
    public function load(int $serviceId)
    {
        if (array_key_exists($serviceId, $this->_services)) {
            try {
                $service = $this->_objectManager->create(
                    '\Zamoroka\PayPalAllCurrencies\Model\CurrencyService\\' . $this->_services[$serviceId]['className']
                );

                return $service;
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->_services;
    }
}
