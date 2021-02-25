<?php 

namespace WebePower\Sitemap\Setup;
 
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
 
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('cms_page'),
                    'use_in_sitemap',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'default' => 1,
                        'nullable' => false,
                        'comment' => 'Include Page in HTML Sitemap',
                    ]
                );
            $setup->getConnection()->update($setup->getTable('cms_page'),['use_in_sitemap'=>0],['identifier=?' => 'no-route']);
        }
 
        $setup->endSetup();
    }
}