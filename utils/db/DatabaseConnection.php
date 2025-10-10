<?php
class DatabaseConnection
{
    private static $instance = null;
    private static $config = [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'dbname' => 'db',
        'user' => 'SYSDBA',
        'pass' => 'masterkey',
        'charcet' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ];

    private function __construct() {}
    private function __clone() {}

    public static function setConfig($arr)
    {
        foreach ($arr as $k => $v) {
            self::$config[$k] = $v;
        }
    }

    public static function fromEnv()
    {
        self::setConfig([
            'host'    => getenv('DB_HOST') ?: self::$config['host'],
            'port'    => getenv('DB_PORT') ?: self::$config['port'],
            'dbname'  => getenv('DB_NAME') ?: self::$config['dbname'],
            'user'    => getenv('DB_USER') ?: self::$config['user'],
            'pass'    => getenv('DB_PASS') ?: self::$config['pass'],
            'charset' => getenv('DB_CHARSET') ?: self::$config['charset'],
        ]);
    }

    public static function conn()
    {
        if (self::$instance === null) {
            $cfg = self::$config;
            $dns = $cfg['driver'] . ':host=' . $cfg['host'] . ';port=' . $cfg['port'] . ';dbname=' . $cfg['dbname'] . ';charset=' . $cfg['charset'];
            self::$instance = new PDO($dns, $cfg['user'], $cfg['pass'], $cfg['options']);
        }
        return self::$instance;
    }
}
