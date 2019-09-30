<?php


namespace Sys\Store;


use Medoo\Medoo;
use Sys\Application;
use Sys\Config;
use Auryn\InjectionException;
use PDOStatement;
use Exception;
use Medoo\Raw;

abstract class Database
{
    /**
     * @var Medoo
     */
    private static $medoo;

    /**
     * @throws InjectionException
     */
    public static function getMedoo(): Medoo
    {
        if (empty(self::$medoo)) {
            $config = Application::instance()->make(Config::class)->get('database');
            $dbConfig = $config[$config['default_database']];
            self::$medoo = new Medoo($dbConfig);
        }
        return self::$medoo;
    }

    /**
     * @param string $sql
     * @param array $bind
     * @return bool|PDOStatement
     * @throws InjectionException
     */
    public static function query(string $sql, array $bind = [])
    {
        return self::getMedoo()->query($sql, $bind);
    }

    /**
     * @param callable $call
     * @return bool
     * @throws InjectionException
     * @throws Exception
     */
    public static function action(callable $call)
    {
        return self::getMedoo()->action($call);
    }

    /**
     * @throws InjectionException
     */
    public static function beginTransaction()
    {
        self::getMedoo()->pdo->beginTransaction();
    }

    /**
     * @throws InjectionException
     */
    public static function commit()
    {
        self::getMedoo()->pdo->commit();
    }

    /**
     * @throws InjectionException
     */
    public static function rollback()
    {
        self::getMedoo()->pdo->rollBack();
    }

    /**
     * @param $string
     * @param array $map
     * @return Raw
     * @throws InjectionException
     */
    public static function raw($string, $map = [])
    {
        return self::getMedoo()->raw($string, $map);
    }

    /**
     * @return array
     * @throws InjectionException
     */
    public static function info()
    {
        return self::getMedoo()->info();
    }

    /**
     * @return mixed|string|string[]|null
     * @throws InjectionException
     */
    public static function last()
    {
        return self::getMedoo()->last();
    }

    /**
     * @return array
     * @throws InjectionException
     */
    public static function log()
    {
        return self::getMedoo()->log();
    }

    /**
     * @return array|null
     * @throws InjectionException
     */
    public static function error()
    {
        return self::getMedoo()->error();
    }
}
