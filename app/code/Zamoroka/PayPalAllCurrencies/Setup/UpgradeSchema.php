<?php

namespace Zamoroka\PayPalAllCurrencies\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class UpgradeSchema
 *
 * @package Zamoroka\PayPalAllCurrencies\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface   $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.2.0') < 0) {
            $tableName = $setup->getTable('zamoroka_paypalallcurrencies_rates');
            if ($setup->getConnection()->isTableExists($tableName) != true) {
                $table = $setup->getConnection()
                               ->newTable($tableName)
                               ->addColumn(
                                   'entity_id',
                                   Table::TYPE_INTEGER,
                                   null,
                                   [
                                       'identity' => true,
                                       'unsigned' => true,
                                       'nullable' => false,
                                       'primary'  => true
                                   ],
                                   'ID'
                               )
                               ->addColumn(
                                   'service_id',
                                   Table::TYPE_INTEGER,
                                   null,
                                   [
                                       'nullable' => false,
                                   ],
                                   'Currency service id'
                               )
                               ->addColumn(
                                   'base_currency_code',
                                   Table::TYPE_TEXT,
                                   null,
                                   ['nullable' => false, 'default' => ''],
                                   'Currency code in website'
                               )
                               ->addColumn(
                                   'paypal_currency_code',
                                   Table::TYPE_TEXT,
                                   null,
                                   ['nullable' => false, 'default' => ''],
                                   'Currency code in Paypal'
                               )
                               ->addColumn(
                                   'rate',
                                   Table::TYPE_DECIMAL,
                                   '12,4',
                                   ['nullable' => false, 'default' => '0.0000'],
                                   'Rate'
                               )
                               ->addColumn(
                                   'updated_at',
                                   Table::TYPE_DATETIME,
                                   null,
                                   ['nullable' => false],
                                   'Updated At'
                               )
                               ->setOption('type', 'InnoDB')
                               ->setOption('charset', 'utf8');
                $setup->getConnection()->createTable($table);
            }
        }

        $setup->endSetup();
    }
}
