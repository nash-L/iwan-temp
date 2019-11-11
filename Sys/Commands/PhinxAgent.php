<?php


namespace Sys\Commands;


use Phinx\Console\PhinxApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Sys\Application;
use Sys\Config;
use Auryn\InjectionException;
use Exception;
use Sys\Console;

abstract class PhinxAgent extends Command
{
    /**
     * @param $command
     * @param bool $withConfig
     * @throws InjectionException
     * @throws Exception
     */
    protected function executeAgent($command, $withConfig = false)
    {
        $argv = $_SERVER['argv'];
        $application = new PhinxApplication(Console::VERSION);
        $argv[0] = 'phinx';
        $argv[1] = $command;
        if ($withConfig) {
            $argv[] = '-c';
            $argv[] = PhinxAgent::getConfigFilePath();
        }
        $application->setName(Console::NAME);
        $application->run(new ArgvInput($argv));
    }

    /**
     * @return string
     * @throws InjectionException
     */
    public static function getConfigFilePath()
    {
        $config = Application::instance()->make(Config::class);
        self::makeDir($configFileDir = $config->get('runtime_dir'));
        $configFile = $configFileDir . '/migrate.json';
        $migrateConfig = $config->get('migrate');
        $migrateConfig['environments'] = $config->get('database');
        foreach ($migrateConfig['environments'] as $k => $v) {
            if ($k === 'default_database') { continue; }
            $migrateConfig['environments'][$k] = self::exchange($v);
        }
        $migrateConfig['environments']['default_migration_table'] = $migrateConfig['default_migration_table'];
        unset($migrateConfig['default_migration_table']);
        file_put_contents($configFile, json_encode($migrateConfig));
        return $configFile;
    }

    private static function makeDir (string $dir) {
        $dirArr = array_filter(explode('/', $dir));
        $dir = '/';
        foreach ($dirArr as $subDir) {
            $dir .= $subDir;
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            $dir .= '/';
        }
    }

    private static function exchange (array $conf) {
        $result = ['adapter' => ''];
        switch ($conf['database_type']) {
            case 'sqlite':
                $result = [
                    'adapter' => 'sqlite',
                ];
                if ($conf['database_file'] === ':memory:') {
                    $result['memory'] = true;
                } else {
                    $arr = explode('.', $conf['database_file']);
                    $result['name'] = $arr[0];
                    $result['suffix'] = isset($arr[1]) ? ".{$arr[1]}" : '';
                }
                return $result;
            case 'mariadb': case 'mysql':
            $result['adapter'] = 'mysql';
            break;
            case 'mssql':
                $result['adapter'] = 'sqlsrv';
                break;
            case 'pgsql':
                $result['adapter'] = 'pgsql';
                break;
            case 'oracle':
                echo '暂不支持oracle数据库迁移', PHP_EOL;
                exit;
            case 'sybase':
                echo '暂不支持sybase数据库迁移', PHP_EOL;
                exit;
        }
        $result = array_merge($result, [
            'host' => $conf['server'],
            'name' => $conf['database_name'],
            'user' => $conf['username'],
            'pass' => $conf['password']
        ]);
        isset($conf['port']) && $conf['port'] && $result['port'] = $conf['port'];
        isset($conf['charset']) && $conf['charset'] && $result['charset'] = $conf['charset'];
        isset($conf['collation']) && $conf['collation'] && $result['collation'] = $conf['collation'];
        isset($conf['prefix']) && $conf['prefix'] && $result['table_prefix'] = $conf['prefix'];
        return $result;
    }
}