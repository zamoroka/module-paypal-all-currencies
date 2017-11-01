<?php

namespace Zamoroka\PayPalAllCurrencies\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Zamoroka\PayPalAllCurrencies\Helper\Data;

/**
 * Class TestCurrencyConverter
 *
 * @package Zamoroka\PayPalAllCurrencies\Controller\Adminhtml\System\Config
 */
class TestCurrencyConverter extends Action
{
    protected $resultJsonFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param Context     $context
     * @param JsonFactory $resultJsonFactory
     * @param Data        $helper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Data $helper
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Test currency service
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $result */
        $result = $this->resultJsonFactory->create();

        $serviceId = $this->getRequest()->getPost('serviceId');

        /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface $currencyConverter */
        $currencyConverter = $this->_objectManager->create(
            '\Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory'
        )->load($serviceId);

        if ($currencyConverter) {
            $currencyConverter->setPayPalCurrencyCode($this->getRequest()->getPost('payPalCurrency'));

            return $result->setData(
                [
                    'success' => true,
                    'info'    => sprintf(
                        '1 %s = %s %s',
                        $currencyConverter->getStoreCurrencyCode(),
                        $currencyConverter->exchangeFromService(1),
                        $currencyConverter->getPayPalCurrencyCode()
                    )
                ]
            );
        } else {
            return $result->setData(
                [
                    'success' => false,
                    'info'    => sprintf('Error. Can`t load service with id: %s', $serviceId)
                ]
            );
        }
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Zamoroka_PayPalAllCurrencies::config');
    }
}
