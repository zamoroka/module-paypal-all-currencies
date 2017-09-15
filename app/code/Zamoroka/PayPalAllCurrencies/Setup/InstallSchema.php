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
                $itemTable, 'price_in_paypal_currency', [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'length'     => '12,4',
                    'nullable' => true,
                    'comment'  => 'Price in paypal currency',
                    'default'  => '0'
                ]
            );
        }

        foreach ($orderTables as $orderTable) {
            $connection->addColumn(
                $orderTable, 'subtotal_in_paypal_currency', [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length'     => '12,4',
                    'comment'  => 'Subtotal in paypal currency',
                    'default'  => '0'
                ]
            );
            $connection->addColumn(
                $orderTable, 'grand_total_in_paypal_currency', [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length'     => '12,4',
                    'comment'  => 'Grand total in paypal currency',
                    'default'  => '0'
                ]
            );
            $connection->addColumn(
                $orderTable, 'shipping_amount_in_paypal_currency', [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length'     => '12,4',
                    'comment'  => 'Shipping amount in paypal currency',
                    'default'  => '0'
                ]
            );
            $connection->addColumn(
                $orderTable, 'paypal_currency_code', [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'length'   => 20,
                    'comment'  => 'Paypal currency code',
                    'default'  => '0'
                ]
            );
        }

        $installer->endSetup();
    }
}
