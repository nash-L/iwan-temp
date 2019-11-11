<?php


namespace Sys\Migration\ColumnType;


use Phinx\Db\Adapter\MysqlAdapter;

abstract class Mysql
{
    const STRING = 'string';
    const INTEGER = 'integer';
    const FLOAT = 'float';
    const DECIMAL = 'decimal';
    const TEXT = 'text';
    const TIMESTAMP = 'timestamp';
    const SET = 'set';
    const ENUM = 'enum';

    const LIMIT_TEXT_TINY = MysqlAdapter::TEXT_TINY;
    const LIMIT_TEXT_SMALL = MysqlAdapter::TEXT_SMALL;
    const LIMIT_TEXT_REGULAR = MysqlAdapter::TEXT_REGULAR;
    const LIMIT_TEXT_MEDIUM = MysqlAdapter::TEXT_MEDIUM;
    const LIMIT_TEXT_LONG = MysqlAdapter::TEXT_LONG;
}
