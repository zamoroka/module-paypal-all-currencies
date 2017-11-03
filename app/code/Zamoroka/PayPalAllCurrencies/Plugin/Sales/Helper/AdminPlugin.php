<?php

namespace Zamoroka\PayPalAllCurrencies\Plugin\Sales\Helper;

use Magento\Sales\Helper\Admin;
use Magento\Sales\Model\Order;
use Zamoroka\PayPalAllCurrencies\Helper\Data;

/**
 * Class AdminPlugin
 *
 * @package Zamoroka\PayPalAllCurrencies\Plugin\Sales\Helper
 */
class AdminPlugin
{
    /** @var \Zamoroka\PayPalAllCurrencies\Helper\Data $helper */
    protected $helper;

    /**
     * OrderPlugin constructor.
     *
     * @param \Zamoroka\PayPalAllCurrencies\Helper\Data                  $helper
     * @param \Zamoroka\PayPalAllCurrencies\Model\CurrencyServiceFactory $currencyServiceFactory
     * @param \Psr\Log\LoggerInterface                                   $logger
     * @param \Magento\Framework\Message\ManagerInterface                $messageManager
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Sales\Helper\Admin $adminHelper
     * @param                             $dataObject
     * @param                             $basePrice
     * @param                             $price
     * @param bool                        $strong
     * @param string                      $separator
     * @return array
     */
    public function beforeDisplayPrices(
        Admin $adminHelper,
        $dataObject,
        $basePrice,
        $price,
        $strong = false,
        $separator = '<br/>'
    ) {
        $order = ($dataObject instanceof Order) ? $dataObject : $dataObject->getOrder();

        if ($price && $order && $this->helper->isOrderPlacedByPaypal($order)) {
            $price = $order->getPaypalRate() * (float)$price;
            $order->setOrderCurrencyCode($order->getPaypalCurrencyCode());
        }

        return [$order, $basePrice, $price, $strong, $separator];
    }
}
