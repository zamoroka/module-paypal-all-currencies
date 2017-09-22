<?php

namespace Zamoroka\PayPalAllCurrencies\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 *
 * @package Zamoroka\PayPalAllCurrencies\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface   $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $itemTables = [
            $installer->getTable('quote_item'),
            $installer->getTable('sales_order_item'),
            $installer->getTable('sales_invoice_item'),
            $installer->getTable('sales_creditmemo_item'),
        ];

        $orderTables = [
            $installer->getTable('quote'),
            $installer->getTable('sales_order'),
            $installer->getTable('sales_invoice'),
            $installer->getTable('sales_creditmemo'),
        ];

        $connection = $installer->getConnection();

        foreach ($itemTables as $itemTable) {
            $connection->addColumn(
                $itemTable, 'paypal_price', $this->getAmountConfig('Price in paypal currency')
            );

            $connection->addColumn(
                $itemTable, 'paypal_row_total', $this->getAmountConfig('Row total in paypal currency')
            );
        }

        foreach ($orderTables as $orderTable) {
            $connection->addColumn(
                $orderTable, 'paypal_subtotal', $this->getAmountConfig('Subtotal in paypal currency')
            );
            $connection->addColumn(
                $orderTable, 'paypal_grand_total', $this->getAmountConfig('Grand total in paypal currency')
            );
            $connection->addColumn(
                $orderTable, 'paypal_tax_amount', $this->getAmountConfig('Tax in paypal currency')
            );
            $connection->addColumn(
                $orderTable, 'paypal_shipping_amount', $this->getAmountConfig('Shipping in paypal currency')
            );
            $connection->addColumn(
                $orderTable, 'paypal_discount_amount', $this->getAmountConfig('Discount in paypal currency')
            );
            $connection->addColumn(
                $orderTable, 'paypal_rate', $this->getAmountConfig('Paypal rate')
            );
            $connection->addColumn(
                $orderTable, 'paypal_currency_code', [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'length'   => 20,
                    'comment'  => 'Paypal currency code'
                ]
            );
        }

        $installer->endSetup();
    }

    /**
     * @param $comment
     * @return array
     */
    public function getAmountConfig($comment)
    {
        return [
            'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            'nullable' => true,
            'length'   => '12,4',
            'comment'  => $comment,
            'default'  => '0'
        ];
    }
}
