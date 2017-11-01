<?php

namespace Zamoroka\PayPalAllCurrencies\Preference\Payment\Model\Cart\SalesModel;

use Zamoroka\PayPalAllCurrencies\Helper\Data;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory;

/**
 * @method float setPaypalSubtotal(float $amt)
 * @method float setPaypalGrandTotal(float $amt)
 * @method float setPaypalShippingAmount(float $amt)
 * @method float setPaypalDiscountAmount(float $amt)
 * @method float setPaypalTaxAmount(float $amt)
 * @method float setPaypalRate(float $amt)
 * @method string setPaypalCurrencyCode(string $code)
 * Wrapper for \Magento\Quote\Model\Quote sales model
 */
class Quote extends \Magento\Payment\Model\Cart\SalesModel\Quote implements SalesModelInterface
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $helper */
    protected $helper;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory */
    protected $currencyServiceFactory;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface|null $currencyService */
    protected $currencyService = null;

    /**
     * @param \Magento\Quote\Model\Quote                                 $quoteModel
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data                  $helper
     * @param \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory
     */
    public function __construct(
        \Magento\Quote\Model\Quote $quoteModel,
        Data $helper,
        CurrencyServiceFactory $currencyServiceFactory
    ) {
        parent::__construct($quoteModel);
        $this->helper = $helper;
        $this->currencyServiceFactory = $currencyServiceFactory;
    }

    /**
     * {@inheritdoc}
     * used in Magento\Paypal\Model\Cart, function _validate()
     */
    public function getDataUsingMethod($key, $args = null)
    {
        if ($key == 'base_grand_total') {
            return $this->getCurrencyService()->exchange(parent::getDataUsingMethod($key, $args));
        }

        return parent::getDataUsingMethod($key, $args);
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

    /**
     * @return float
     */
    public function getPaypalSubtotal()
    {
        return $this->_salesModel->getPaypalSubtotal();
    }

    /**
     * @return float
     */
    public function getPaypalGrandTotal()
    {
        return $this->_salesModel->getPaypalGrandTotal();
    }

    /**
     * @return float
     */
    public function getPaypalShippingAmount()
    {
        return $this->_salesModel->getPaypalShippingAmount();
    }

    /**
     * @return float
     */
    public function getPaypalDiscountAmount()
    {
        return $this->_salesModel->getPaypalDiscountAmount();
    }

    /**
     * @return float
     */
    public function getPaypalTaxAmount()
    {
        return $this->_salesModel->getPaypalTaxAmount();
    }

    /**
     * @return float
     */
    public function getPaypalRate()
    {
        return $this->_salesModel->getPaypalRate();
    }

    /**
     * @return string
     */
    public function getPaypalCurrencyCode()
    {
        return $this->_salesModel->getPaypalCurrencyCode();
    }
}
