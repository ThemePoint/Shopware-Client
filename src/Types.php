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

final class Types
{
    const HTTP_GET = 'GET';
    const HTTP_PUT = 'PUT';
    const HTTP_POST = 'POST';
    const HTTP_DELETE = 'DELETE';

    const API_GET_ITEM = 'GET_ITEM';
    const API_GET_LIST = 'GET_LIST';
    const API_CREATE = 'CREATE_ITEM';
    const API_UPDATE_ITEM = 'UPDATE_ITEM';
    const API_UPDATE_LIST = 'UPDATE_LIST';
    const API_DELETE_ITEM = 'DELETE_ITEM';
    const API_DELETE_LIST = 'DELETE_LIST';

    private static $validHttpMethods = array(
        self::API_GET_ITEM => self::HTTP_GET,
        self::API_GET_LIST => self::HTTP_GET,
        self::API_CREATE => self::HTTP_POST,
        self::API_UPDATE_ITEM => self::HTTP_PUT,
        self::API_UPDATE_LIST => self::HTTP_PUT,
        self::API_DELETE_ITEM => self::HTTP_DELETE,
        self::API_DELETE_LIST => self::HTTP_DELETE,
    );

    private static $validApiMethods = array(
        self::API_GET_ITEM,
        self::API_GET_LIST,
        self::API_CREATE,
        self::API_UPDATE_ITEM,
        self::API_UPDATE_LIST,
        self::API_DELETE_ITEM,
        self::API_DELETE_LIST,
    );

    public static function getHttpMethod(string $apiMethod): string
    {
        return self::$validHttpMethods[$apiMethod];
    }

    public static function getValidApiMethods(): array
    {
        return self::$validApiMethods;
    }
}
