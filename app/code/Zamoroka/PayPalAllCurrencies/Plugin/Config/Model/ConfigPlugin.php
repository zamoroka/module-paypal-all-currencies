<?php

namespace Zamoroka\PayPalAllCurrencies\Plugin\Config\Model;

use Magento\Config\Model\Config;
use Zamoroka\PayPalAllCurrencies\Helper\Data;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory;
use Zamoroka\PayPalAllCurrencies\Model\RatesFactory;

/**
 * Class ConfigPlugin
 */
class ConfigPlugin
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $_helper */
    protected $_helper;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory */
    protected $currencyServiceFactory;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\RatesFactory $ratesFactory */
    protected $ratesFactory;

    /**
     * ConfigPlugin constructor.
     *
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data                  $helper
     * @param \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory
     * @param \Zamoroka\PayPalAllCurrencies\Model\RatesFactory           $ratesFactory
     */
    public function __construct(
        Data $helper,
        CurrencyServiceFactory $currencyServiceFactory,
        RatesFactory $ratesFactory
    ) {
        $this->_helper = $helper;
        $this->currencyServiceFactory = $currencyServiceFactory;
        $this->ratesFactory = $ratesFactory;
    }

    /**
     * @param Config $config
     * @param Config $result
     * @return Config
     */
    public function afterSave(Config $config, $result)
    {
        if ($config->getSection() === 'zamoroka_paypalallcurrencies') {
            /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface $currencyService */
            $currencyService = $this->currencyServiceFactory->load($this->_helper->getCurrencyServiceId());

            /** @var \Zamoroka\PayPalAllCurrencies\Model\Rates $ratesModel */
            $ratesModel = $this->ratesFactory->create();
            $ratesModel->updateRateFromService($currencyService);
            $ratesModel->save();
        }

        return $result;
    }
}
