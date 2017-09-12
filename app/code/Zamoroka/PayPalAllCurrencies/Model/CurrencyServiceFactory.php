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
    /** @var array $_services */
    protected $_services
        = [
            0 => [
                'className' => 'FreeCurrencyConverter'
            ],
            1 => [
                'className' => 'ApiAppnexus'
            ],
            2 => [
                'className' => 'FinanceGoogle'
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
     * @return null|CurrencyServiceInterface
     */
    public function load(int $serviceId)
    {
        if (array_key_exists($serviceId, $this->_services)) {
            return $this->_objectManager->create(
                '\Zamoroka\PayPalAllCurrencies\Model\CurrencyService\\' . $this->_services[$serviceId]['className']
            );
        }

        return null;
    }
}
