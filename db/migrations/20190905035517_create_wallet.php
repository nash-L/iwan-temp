<?php

use Sys\Migration\AbstractMigration;
use Sys\Migration\ColumnType\Mysql;

class CreateWallet extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('wallet', ['comment' => '钱包表', 'collation' => 'utf8mb4_general_ci', 'signed' => false]);
        $table->addColumn('account_id', Mysql::INTEGER, ['signed' => false, 'comment' => '账户id，关联account表id字段'])
            ->addColumn('amount', Mysql::INTEGER, ['default' => 0, 'comment' => '钱包金额'])
            ->addColumn('type', Mysql::ENUM, ['values' => ['money'/*现金钱包*/], 'default' => 'money', 'comment' => '钱包类型'])
            ->addColumn('create_time', Mysql::TIMESTAMP, ['default' => 'CURRENT_TIMESTAMP', 'timezone' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', Mysql::TIMESTAMP, ['null' => true, 'update' => 'CURRENT_TIMESTAMP', 'timezone' => true, 'comment' => '修改时间'])
            ->addIndex('account_id')
            ->create();
    }
}
