<?php

namespace Zamoroka\PayPalAllCurrencies\Plugin\Paypal\Model\Api;

use Magento\Paypal\Model\Api\Nvp;
use Zamoroka\PayPalAllCurrencies\Helper\Data;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory;

/**
 * Class NvpPlugin
 *
 * @package Zamoroka\PayPalAllCurrencies\Model\Plugin\Paypal\Api
 */
class NvpPlugin
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $helper */
    protected $helper;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory */
    protected $currencyServiceFactory;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface|null $currencyService */
    protected $currencyService = null;

    /**
     * NvpPlugin constructor.
     *
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data                  $helper
     * @param \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory
     */
    public function __construct(
        Data $helper,
        CurrencyServiceFactory $currencyServiceFactory
    ) {
        $this->helper = $helper;
        $this->currencyServiceFactory = $currencyServiceFactory;
    }

    /**
     * @TODO-zamoroka: remove this plugin. Find another solution
     * used in Magento\Paypal\Model\Express\Checkout::start()
     *
     * @param \Magento\Paypal\Model\Api\Nvp $nvp
     * @param                               $key
     * @param null                          $value
     * @return array
     */
    public function beforeSetData(Nvp $nvp, $key, $value = null)
    {
        if ($this->helper->isModuleEnabled()) {
            switch ($key) {
                case 'amount':
                    $value = $this->getCurrencyService()->exchange($value);
                    break;
                case 'currency_code':
                    $value = $this->helper->getPayPalCurrency();
                    break;
                default:
                    break;
            }
        }

        return [$key, $value];
    }

    /**
     * @return false|null|\Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface
     */
    public function getCurrencyService()
    {
        if (!$this->currencyService) {
            $this->currencyService = $this->currencyServiceFactory->load($this->helper->getCurrencyServiceId());
        }

        return $this->currencyService;
    }
}
