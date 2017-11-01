<?php

namespace Zamoroka\PayPalAllCurrencies\Preference\Paypal\Model;

/**
 * Class Cart
 *
 * @package \Zamoroka\PayPalAllCurrencies\Model\Preference\Paypal
 */
class Cart extends \Magento\Paypal\Model\Cart
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $helper */
    protected $helper;

    /**
     * NvpPlugin constructor.
     *
     * @param \Magento\Payment\Model\Cart\SalesModel\Factory $salesModelFactory
     * @param \Magento\Framework\Event\ManagerInterface      $eventManager
     * @param \Magento\Quote\Api\Data\CartInterface          $salesModel
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data      $helper
     */
    public function __construct(
        \Magento\Payment\Model\Cart\SalesModel\Factory $salesModelFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        $salesModel,
        \Zamoroka\PayPalAllCurrencies\Helper\Data $helper
    ) {
        parent::__construct($salesModelFactory, $eventManager, $salesModel);
        $this->helper = $helper;
    }

    /**
     * Import items from sales model with workarounds for PayPal
     *
     * @return void
     */
    protected function _importItemsFromSalesModel()
    {
        if ($this->helper->isModuleEnabled()) {
            $this->_salesModelItems = [];

            foreach ($this->_salesModel->getAllItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }

                $amount = $item->getOriginalItem()->getPaypalPrice();
                $qty = $item->getQty();

                $subAggregatedLabel = '';

                // workaround in case if item subtotal precision is not compatible with PayPal (.2)
                if ($amount - round($amount, 2)) {
                    $amount = $amount * $qty;
                    $subAggregatedLabel = ' x' . $qty;
                    $qty = 1;
                }

                // aggregate item price if item qty * price does not match row total
                $itemBaseRowTotal = $item->getOriginalItem()->getPaypalRowTotal();
                if ($amount * $qty != $itemBaseRowTotal) {
                    $amount = (double)$itemBaseRowTotal;
                    $subAggregatedLabel = ' x' . $qty;
                    $qty = 1;
                }

                $this->_salesModelItems[] = $this->_createItemFromData(
                    $item->getName() . $subAggregatedLabel,
                    $qty,
                    $amount
                );
            }

            /** @TODO-zamoroka: check this, when data will be saved in order and for instanceof \Magento\Payment\Model\Cart\SalesModel\Order */
            $this->addSubtotal($this->_salesModel->getPaypalSubtotal());
            $this->addTax($this->_salesModel->getPaypalTaxAmount());
            $this->addShipping($this->_salesModel->getPaypalShippingAmount());
            $this->addDiscount(abs($this->_salesModel->getPaypalDiscountAmount()));
        } else {
            parent::_importItemsFromSalesModel();
        }
    }
}
