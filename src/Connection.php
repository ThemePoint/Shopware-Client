<?php
/**
 * Shopware Client
 * Copyright (c) 2019 ThemePoint
 *
 * @author Hendrik Legge <hendrik.legge@themepoint.de>
 * @version 1.0.0
 * @package shopbase.shopware.client
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Shopbase\ShopwareClient;

use Shopbase\ShopwareClient\Exceptions\ConnectionException;

final class Connection
{
    private static $globalConnection;

    private $url;

    private $apiPath;

    private $username;

    private $key;

    private $connection;

    private $isGlobal = false;

    private $timeout;

    private $timeoutInt;

    public function __construct(string $username, string $key, string $url, string $apiPath = 'api', int $timeout = null)
    {
        $this->url = $this->validateUrl($url, $apiPath);
        $this->apiPath = $apiPath;
        $this->username = $username;
        $this->key = $key;

        if ($timeout !== null && $timeout > 0) {
            $this->timeout = new \DateTimeImmutable(strtotime(sprintf('+ %s seconds', $timeout)));
            $this->timeoutInt = $timeout;
        }
    }

    public function setIsGlobal(): self
    {
        $this->isGlobal = true;

        return $this;
    }

    public function getConnection(bool $useGlobalConnection = false, string $clientName = 'API Client')
    {
        if ($useGlobalConnection) {
            if (static::$globalConnection === null) {
                throw new ConnectionException('There is no global connection stored.');
            }

            $this->connection = static::$globalConnection;
        }

        if ($this->connection === null) {
            $this->connection = curl_init();

            curl_setopt($this->connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->connection, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($this->connection, CURLOPT_USERAGENT, $clientName);
            curl_setopt($this->connection, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
            curl_setopt($this->connection, CURLOPT_USERPWD, $this->username . ':' . $this->key);
            curl_setopt($this->connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));

            if ($this->isGlobal) {
                static::$globalConnection = $this->connection;
            }
        }

        return $this->connection;
    }

    public function reconnect()
    {
        return new self($this->username, $this->key, $this->url, $this->apiPath, $this->timeoutInt);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getTimeout(): ?\DateTimeImmutable
    {
        return $this->timeout;
    }

    private function validateUrl(string $url, string $apiPath): string
    {
        $url = rtrim($url, '/') . '/';

        $result = str_replace('//', '/', sprintf('%s/%s/', $url, $apiPath));
        $result = str_replace(':/', '://', $result);

        return $result;
    }
}
