<?php

namespace Zamoroka\PayPalAllCurrencies\Plugin\Sales\Model;

use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order\Item;
use Magento\Sales\Model\OrderRepository;
use Psr\Log\LoggerInterface;
use Zamoroka\PayPalAllCurrencies\Helper\Data;
use Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory;
use Zamoroka\PayPalAllCurrencies\Preference\Payment\Model\Cart\SalesModel\Order as SalesModelOrder;

/**
 * Class OrderPlugin
 *
 * @package Zamoroka\PayPalAllCurrencies\Plugin\Sales\Model
 */
class OrderRepositoryPlugin
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
     * OrderPlugin constructor.
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
     * @param \Magento\Sales\Model\OrderRepository                   $orderRepository
     * @param \Magento\Sales\Api\Data\OrderInterface|SalesModelOrder $entity
     * @return array
     */
    public function beforeSave(OrderRepository $orderRepository, OrderInterface $entity)
    {
        if ($this->helper->isModuleEnabled() && $this->helper->isOrderPlacedByPaypal($entity)) {
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
     * @param string                                 $code
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @param \Magento\Sales\Model\Order\Item|null   $item
     * @return float
     */
    public function gePaypalAmtFromItem($code, OrderInterface $order, Item $item = null)
    {
        $amt = 0;

        /** @var \Magento\Sales\Model\Order\Address $address */
        $address = $order->getIsVirtual() ? $order->getBillingAddress() : $order->getShippingAddress();

        try {
            switch ($code) {
                case 'subtotal':
                    $amt = $order->getBaseSubtotal();
                    break;
                case 'grand_total':
                    $amt = $order->getBaseGrandTotal();
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
