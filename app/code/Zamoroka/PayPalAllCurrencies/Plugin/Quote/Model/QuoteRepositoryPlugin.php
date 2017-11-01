<?php

namespace Zamoroka\PayPalAllCurrencies\Plugin\Quote\Model;

use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\QuoteRepository;
use Psr\Log\LoggerInterface;
use Zamoroka\PayPalAllCurrencies\Helper\Data;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory;
use Zamoroka\PayPalAllCurrencies\Preference\Payment\Model\Cart\SalesModel\Quote as SalesModelQuote;

/**
 * Class QuoteRepositoryPlugin
 *
 * @package Zamoroka\PayPalAllCurrencies\Plugin\Quote\Model
 */
class QuoteRepositoryPlugin
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $helper */
    protected $helper;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory */
    protected $currencyServiceFactory;

    /** @var \Zamoroka\PayPalAllCurrencies\Model\CurrencyService\CurrencyServiceInterface|null $currencyService */
    protected $currencyService = null;

    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;

    /** @var \Magento\Framework\Message\ManagerInterface */
    protected $messageManager;

    /**
     * QuoteRepositoryPlugin constructor.
     *
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data                  $helper
     * @param \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory
     * @param \Psr\Log\LoggerInterface                                   $logger
     * @param \Magento\Framework\Message\ManagerInterface                $messageManager
     */
    public function __construct(
        Data $helper,
        CurrencyServiceFactory $currencyServiceFactory,
        LoggerInterface $logger,
        ManagerInterface $messageManager
    ) {
        $this->helper = $helper;
        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->currencyServiceFactory = $currencyServiceFactory;
    }

    /**
     * @param \Magento\Quote\Model\QuoteRepository  $quoteRepository
     * @param \Magento\Quote\Api\Data\CartInterface|SalesModelQuote $entity
     * @return array
     */
    public function beforeSave(QuoteRepository $quoteRepository, CartInterface $entity)
    {
        if ($this->helper->isModuleEnabled()
            && in_array(
                $entity->getPayment()->getMethod(), $this->helper->getPaypalPaymentMethods()
            )) {
            try {
                foreach ($entity->getAllItems() as $item) {
                    $amt = $this->gePaypalAmtFromItem('item_price', $entity, $item);
                    $amtTotal = $this->gePaypalAmtFromItem('item_row_total', $entity, $item);
                    $item->setPaypalPrice($amt);
                    $item->setPaypalRowTotal($amtTotal);
                }

                $entity->setPaypalSubtotal($this->gePaypalAmtFromItem('subtotal', $entity));
                $entity->setPaypalGrandTotal($this->gePaypalAmtFromItem('grand_total', $entity));

                $entity->setPaypalShippingAmount($this->gePaypalAmtFromItem('shipping', $entity));
                $entity->setPaypalDiscountAmount($this->gePaypalAmtFromItem('discount', $entity));
                $entity->setPaypalTaxAmount($this->gePaypalAmtFromItem('tax', $entity));

                $entity->setPaypalRate($this->getCurrencyService()->exchange(1));
                $entity->setPaypalCurrencyCode($this->helper->getPayPalCurrency());
            } catch (\Exception $exception) {
                $this->logger->critical($exception->getMessage());
            }
        }

        return [$entity];
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
     * @param string                                $code
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param \Magento\Quote\Model\Quote\Item|null  $item
     * @return float
     */
    public function gePaypalAmtFromItem($code, CartInterface $quote, Item $item = null)
    {
        $amt = 0;

        /** @var \Magento\Quote\Model\Quote\Address $address */
        $address = $quote->getIsVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();

        try {
            switch ($code) {
                case 'subtotal':
                    $amt = $quote->getBaseSubtotal();
                    break;
                case 'grand_total':
                    $amt = $quote->getBaseGrandTotal();
                    break;
                case 'shipping':
                    $amt = $address->getBaseShippingAmount();
                    break;
                case 'discount':
                    $amt = $address->getBaseDiscountAmount();
                    break;
                case 'tax':
                    $amt = $address->getBaseTaxAmount();
                    break;
                case 'item_price':
                    $amt = $item->getBasePrice();
                    break;
                case 'item_row_total':
                    $amt = $item->getBaseRowTotal();
                    break;
                default:
                    break;
            }
        } catch (\Throwable $t) {
            $amt = 0;
            $this->logger->critical($t->getMessage());
            $this->messageManager->addErrorMessage(__('Can`t calculate paypal amount'));
        }

        return $this->getCurrencyService()->exchange($amt);
    }
}
