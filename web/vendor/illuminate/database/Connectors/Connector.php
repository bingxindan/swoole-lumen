<?php

namespace Illuminate\Database\Connectors;

use PDO;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Database\DetectsLostConnections;

class Connector
{
    use DetectsLostConnections;

    /**
     * The default PDO connection options.
     *
     * @var array
     */
    protected $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /**
     * Get the PDO options based on the configuration.
     *
     * @param  array  $config
     * @return array
     */
    public function getOptions(array $config)
    {
        $options = Arr::get($config, 'options', []);

        return array_diff_key($this->options, $options) + $options;
    }

    /**
     * Create a new PDO connection.
     *
     * @param  string  $dsn
     * @param  array   $config
     * @param  array   $options
     * @return PDOFacade
     */
    public function createConnection($dsn, array $config, array $options)
    {
        $username = Arr::get($config, 'username');

        $password = Arr::get($config, 'password');

        try {
            $pdo = $this->createPdoConnection($dsn, $username, $password, $options);
        } catch (Exception $e) {
            $pdo = $this->tryAgainIfCausedByLostConnection(
                $e, $dsn, $username, $password, $options
            );
        }

        return $pdo;
    }

    /**
     * Get the default PDO connection options.
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return $this->options;
    }

    /**
     * Set the default PDO connection options.
     *
     * @param  array  $options
     * @return void
     */
    public function setDefaultOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Handle an exception that occurred during connect execution.
     *
     * @param  \Exception  $e
     * @param  string  $dsn
     * @param  string  $username
     * @param  string  $password
     * @param  array   $options
     * @return PDOFacade
     *
     * @throws \Exception
     */
    protected function tryAgainIfCausedByLostConnection(Exception $e, $dsn, $username, $password, $options)
    {
        if ($this->causedByLostConnection($e)) {
            return $this->createPdoConnection($dsn, $username, $password, $options);
        }

        throw $e;
    }

    /**
     * Create a new PDO connection instance.
     *
     * @return PDOFacade
     */
    protected function createPdoConnection($dsn, $username, $password, $options)
    {
        //数据库访问 尝试catch连接超时，增加重试机制
        return new PDOFacade($dsn, $username, $password, $options);
    }
}
