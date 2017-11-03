<?php

namespace Zamoroka\PayPalAllCurrencies\Plugin\Sales\Block\Adminhtml\Items;

use Magento\Sales\Block\Adminhtml\Items\AbstractItems;
use Zamoroka\PayPalAllCurrencies\Helper\Data;

/**
 * Class AbstractItemsPlugin
 *
 * @package Zamoroka\PayPalAllCurrencies\Plugin\Sales\Block\Adminhtml\Items
 */
class AbstractItemsPlugin
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $helper */
    protected $helper;

    /**
     * OrderPlugin constructor.
     *
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Items\AbstractItems $abstractItems
     * @param                                                    $basePrice
     * @param                                                    $price
     * @param int                                                $precision
     * @param bool                                               $strong
     * @param string                                             $separator
     * @return array
     */
    public function beforeDisplayRoundedPrices(
        AbstractItems $abstractItems,
        $basePrice,
        $price,
        $precision = 2,
        $strong = false,
        $separator = '<br />'
    ) {
        $order = $abstractItems->getOrder();

        if ($price && $order && $this->helper->isOrderPlacedByPaypal($order)) {
            $price = $order->getPaypalRate() * (float)$price;
            $order->setOrderCurrencyCode($order->getPaypalCurrencyCode());
        }

        return [$basePrice, $price, $precision, $strong, $separator];
    }
}
