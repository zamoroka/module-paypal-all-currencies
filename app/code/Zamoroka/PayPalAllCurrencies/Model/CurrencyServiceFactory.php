<?php

namespace Zamoroka\PayPalAllCurrencies\Model;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;
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
     * @var array $services
     */
    protected $services
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

    /** @var \Magento\Framework\ObjectManagerInterface $objectManager */
    protected $objectManager;

    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;

    /**
     * CurrencyConverter constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Psr\Log\LoggerInterface              $logger
     */
    public function __construct(Context $context, LoggerInterface $logger)
    {
        $this->objectManager = $context->getObjectManager();
        $this->logger = $logger;
    }

    /**
     * @param int $serviceId
     * @return false|CurrencyServiceInterface
     */
    public function load(int $serviceId)
    {
        if (array_key_exists($serviceId, $this->services)) {
            try {
                $service = $this->objectManager->create(
                    '\Zamoroka\PayPalAllCurrencies\Model\CurrencyService\\' . $this->services[$serviceId]['className'],
                    ['serviceId' => $serviceId]
                );

                return $service;
            } catch (\Exception $e) {
                $this->logger->addError($e->getMessage());

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
        return $this->services;
    }
}
