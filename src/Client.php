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

use Shopbase\ShopwareClient\Exceptions\ClientException;
use Shopbase\ShopwareClient\Exceptions\TimeoutException;
use Shopbase\ShopwareClient\Interfaces\ClientInterface;
use Shopbase\ShopwareClient\Interfaces\ResourceInterface;

final class Client implements ClientInterface
{
    private $connection;

    private $url;

    public function __construct(Connection $connection)
    {
        if (!$connection instanceof Connection) {
            throw new ClientException('Used connection is not valid');
        }

        $this->url = $connection->getUrl();
        $this->connection = $connection;
    }

    public function get($resource, $item = null, array $params = array()): Response
    {
        if (!\is_object($resource) && !\is_string($resource) || \is_string($resource) && !class_exists($resource) || \is_object($resource) && !$resource instanceof ResourceInterface) {
            throw new ClientException('Failed while loading resource');
        }

        if (!\is_object($resource)) {
            $resource = new $resource();
        }

        return $this->call(
            $resource,
            $item === null ? Types::API_GET_LIST : Types::API_GET_ITEM,
            $item === null ? $resource->getEndpoint() : sprintf('%s/%s', rtrim($resource->getEndpoint(), '/'), $item),
            $params,
            array()
        );
    }

    public function post($resource, array $data, array $params = array()): Response
    {
        if (!\is_object($resource) && !\is_string($resource) || \is_string($resource) && !class_exists($resource) || \is_object($resource) && !$resource instanceof ResourceInterface) {
            throw new ClientException('Failed while loading resource');
        }

        if (!\is_object($resource)) {
            $resource = new $resource();
        }

        return $this->call(
            $resource,
            Types::API_CREATE,
            $resource->getEndpoint(),
            $params,
            $data
        );
    }

    public function create($resource, array $data, array $params = array()): Response
    {
        return $this->post($resource, $data, $params);
    }

    public function put($resource, $item = null, array $data = array(), array $params = array()): Response
    {
        if (!\is_object($resource) && !\is_string($resource) || \is_string($resource) && !class_exists($resource) || \is_object($resource) && !$resource instanceof ResourceInterface) {
            throw new ClientException('Failed while loading resource');
        }

        if (!\is_object($resource)) {
            $resource = new $resource();
        }

        return $this->call(
            $resource,
            $item === null ? Types::API_UPDATE_LIST : Types::API_UPDATE_ITEM,
            $item === null ? $resource->getEndpoint() : sprintf('%s/%s', rtrim($resource->getEndpoint(), '/'), $item),
            $params,
            $data
        );
    }

    public function update($resource, $item = null, array $data = array(), array $params = array()): Response
    {
        return $this->put($resource, $item, $data, $params);
    }

    public function delete($resource, $item = null, array $list = array(), array $params = array()): Response
    {
        if (!\is_object($resource) && !\is_string($resource) || \is_string($resource) && !class_exists($resource) || \is_object($resource) && !$resource instanceof ResourceInterface) {
            throw new ClientException('Failed while loading resource');
        }

        if (!\is_object($resource)) {
            $resource = new $resource();
        }

        return $this->call(
            $resource,
            $item === null ? Types::API_DELETE_LIST : Types::API_DELETE_ITEM,
            $item === null ? $resource->getEndpoint() : sprintf('%s/%s', rtrim($resource->getEndpoint(), '/'), $item),
            $params,
            $list
        );
    }

    public function refreshConnection(): self
    {
        $this->connection = $this->connection->reconnect();
        return $this;
    }

    private function call(ResourceInterface $resource, string $method, string $endpoint, array $params = array(), array $data = array()): Response
    {
        if (!\in_array($method, $resource->getValidTypes())) {
            throw new ClientException(sprintf('Method %s is not valid', $method));
        }

        if (new \DateTimeImmutable() >= $this->connection->getTimeout()) {
            throw new TimeoutException(sprintf('Connection timed out.'));
        }

        $connection = $this->connection->getConnection();

        curl_setopt($connection, CURLOPT_URL, sprintf('%s/%s?%s', rtrim($this->url, '/'), rtrim($endpoint, '?'), !empty($params) ? http_build_query($params) : ''));
        curl_setopt($connection, CURLOPT_CUSTOMREQUEST, Types::getHttpMethod($method));
        curl_setopt($connection, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($connection);
        $httpCode = curl_getinfo($connection, CURLINFO_HTTP_CODE);

        return new Response($result, $httpCode);
    }
}
