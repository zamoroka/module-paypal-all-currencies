<?php

namespace Zamoroka\PayPalAllCurrencies\Cron;

use Zamoroka\PayPalAllCurrencies\Helper\Data;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory;
use Zamoroka\PayPalAllCurrencies\Model\RatesFactory;
use Psr\Log\LoggerInterface;

class UpdateCurrency
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $helper */
    protected $helper;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory */
    protected $currencyServiceFactory;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\RatesFactory $ratesFactory */
    protected $ratesFactory;

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
        $this->helper = $helper;
        $this->currencyServiceFactory = $currencyServiceFactory;
        $this->ratesFactory = $ratesFactory;
        $this->logger = $logger;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface $currencyService */
        $currencyService = $this->currencyServiceFactory->load($this->helper->getCurrencyServiceId());

        /** @var \Zamoroka\PayPalAllCurrencies\Model\Rates $ratesModel */
        $ratesModel = $this->ratesFactory->create();
        $ratesModel->updateRateFromService($currencyService);
        $ratesModel->save();
        $this->logger->info('Rate is updated');

        return $this;
    }
}
