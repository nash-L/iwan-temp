<?php

use Sys\Migration\AbstractMigration;
use Sys\Migration\ColumnType\Mysql;

class CreateAccount extends AbstractMigration
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
        $table = $this->table('account', ['comment' => '用户表', 'collation' => 'utf8mb4_general_ci', 'signed' => false]);
        $table->addColumn('auth_code', Mysql::STRING, ['limit' => 32, 'comment' => '身份标识'])
            ->addColumn('wallet_count', Mysql::INTEGER, ['default' => 0, 'comment' => '钱包金额总计'])
            ->addColumn('coupon_num', Mysql::INTEGER, ['default' => 0, 'comment' => '优惠券数量'])
            ->addColumn('points_count', Mysql::INTEGER, ['default' => 0, 'comment' => '积分数量总计'])
            ->addColumn('create_time', Mysql::TIMESTAMP, ['default' => 'CURRENT_TIMESTAMP', 'timezone' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', Mysql::TIMESTAMP, ['null' => true, 'update' => 'CURRENT_TIMESTAMP', 'timezone' => true, 'comment' => '修改时间'])
            ->addIndex('auth_code', ['unique' => true])
            ->create();
    }
}
