<?php

namespace Zamoroka\PayPalAllCurrencies\Cron;

use Zamoroka\PayPalAllCurrencies\Helper\Data;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory;
use Zamoroka\PayPalAllCurrencies\Model\RatesFactory;
use Psr\Log\LoggerInterface;

class UpdateCurrency
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $_helper */
    protected $_helper;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $_currencyServiceFactory */
    protected $_currencyServiceFactory;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\RatesFactory $_ratesFactory */
    protected $_ratesFactory;

    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;

    /**
     * ConfigPlugin constructor.
     *
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data                  $helper
     * @param \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory
     * @param \Zamoroka\PayPalAllCurrencies\Model\RatesFactory           $ratesFactory
     * @param \Psr\Log\LoggerInterface                                   $logger
     */
    public function __construct(
        Data $helper,
        CurrencyServiceFactory $currencyServiceFactory,
        RatesFactory $ratesFactory,
        LoggerInterface $logger
    ) {
        $this->_helper = $helper;
        $this->_currencyServiceFactory = $currencyServiceFactory;
        $this->_ratesFactory = $ratesFactory;
        $this->logger = $logger;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $this->logger->info(__METHOD__);
        /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface $currencyService */
        $currencyService = $this->_currencyServiceFactory->load($this->_helper->getCurrencyServiceId());
        $this->logger->info($this->_helper->getCurrencyServiceId());

        /** @var \Zamoroka\PayPalAllCurrencies\Model\Rates $ratesModel */
        $ratesModel = $this->_ratesFactory->create();
        $ratesModel->updateRateFromService($currencyService);
        $ratesModel->save();
        $this->logger->info('saved');

        return $this;
    }
}
