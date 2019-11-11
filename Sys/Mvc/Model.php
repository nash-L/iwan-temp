<?php


namespace Sys\Mvc;


use Sys\Store\Database;
use Auryn\InjectionException;
use PDOStatement;
/**
 * Medoo
 * https://medoo.in/api/where
 */
class Model
{
    protected $table;

    /**
     * Model constructor.
     * @param string|null $table
     */
    public function __construct(?string $table = null)
    {
        if (empty($table)) {
            $className = get_class($this);
            $arr = explode('\\', $className);
            $tableClass = lcfirst(array_pop($arr));
            $table = strtolower(preg_replace('/([A-Z])/', '_${1}', $tableClass));
        }
        $this->setTable($table);
    }

    protected function setTable(string $table)
    {
        $this->table = $table;
        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public static function instance(string $table)
    {
        return new self($table);
    }

    /**
     * @param $join
     * @param null $columns
     * @param null $where
     * @return array|bool
     * @throws InjectionException
     */
    public function select($join, $columns = null, $where = null)
    {
        return Database::getMedoo()->select($this->getTable(), $join, $columns, $where);
    }

    /**
     * @param $data
     * @return bool|PDOStatement
     * @throws InjectionException
     */
    public function insert($data)
    {
        return Database::getMedoo()->insert($this->getTable(), $data);
    }

    /**
     * @param $data
     * @param null $where
     * @return bool|PDOStatement
     * @throws InjectionException
     */
    public function update($data, $where = null)
    {
        return Database::getMedoo()->update($this->getTable(), $data, $where);
    }

    /**
     * @param $where
     * @return bool|PDOStatement
     * @throws InjectionException
     */
    public function delete($where)
    {
        return Database::getMedoo()->delete($this->getTable(), $where);
    }

    /**
     * @param $columns
     * @param null $where
     * @return bool|PDOStatement
     * @throws InjectionException
     */
    public function replace($columns, $where = null)
    {
        return Database::getMedoo()->replace($this->getTable(), $columns, $where);
    }

    /**
     * @param null $join
     * @param null $columns
     * @param null $where
     * @return mixed
     * @throws InjectionException
     */
    public function get($join = null, $columns = null, $where = null)
    {
        return Database::getMedoo()->get($this->getTable(), $join, $columns, $where);
    }

    /**
     * @param $join
     * @param null $where
     * @return bool
     * @throws InjectionException
     */
    public function has($join, $where = null)
    {
        return Database::getMedoo()->has($this->getTable(), $join, $where);
    }

    /**
     * @param null $join
     * @param null $columns
     * @param null $where
     * @return array|bool
     * @throws InjectionException
     */
    public function rand($join = null, $columns = null, $where = null)
    {
        return Database::getMedoo()->rand($this->getTable(), $join, $columns, $where);
    }

    /**
     * @param null $join
     * @param null $column
     * @param null $where
     * @return bool|int|mixed|string
     * @throws InjectionException
     */
    public function count($join = null, $column = null, $where = null)
    {
        return Database::getMedoo()->count($this->getTable(), $join, $column, $where);
    }

    /**
     * @param $join
     * @param null $column
     * @param null $where
     * @return bool|int|mixed|string
     * @throws InjectionException
     */
    public function max($join, $column = null, $where = null)
    {
        return Database::getMedoo()->max($this->getTable(), $join, $column, $where);
    }

    /**
     * @param $join
     * @param null $column
     * @param null $where
     * @return bool|int|mixed|string
     * @throws InjectionException
     */
    public function min($join, $column = null, $where = null)
    {
        return Database::getMedoo()->min($this->getTable(), $join, $column, $where);
    }

    /**
     * @param $join
     * @param null $column
     * @param null $where
     * @return bool|int|mixed|string
     * @throws InjectionException
     */
    public function avg($join, $column = null, $where = null)
    {
        return Database::getMedoo()->avg($this->getTable(), $join, $column, $where);
    }

    /**
     * @param $join
     * @param null $column
     * @param null $where
     * @return bool|int|mixed|string
     * @throws InjectionException
     */
    public function sum($join, $column = null, $where = null)
    {
        return Database::getMedoo()->sum($this->getTable(), $join, $column, $where);
    }

    /**
     * @return int|mixed|string
     * @throws InjectionException
     */
    public function id()
    {
        return Database::getMedoo()->id();
    }

    /**
     * @return $this
     * @throws InjectionException
     */
    public function debug()
    {
        Database::getMedoo()->debug();
        return $this;
    }
}
