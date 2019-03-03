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

namespace Shopbase\ShopwareClient\Interfaces;

use Shopbase\ShopwareClient\Response;

interface ClientInterface
{
    /**
     * Get a list or an item form an resource. When the parameter $item is not defined (null)
     * the client will automatically return complete list.
     *
     * @param $resource
     * @param null  $item
     * @param array $params
     *
     * @return Response
     */
    public function get($resource, $item = null, array $params = array()): Response;

    /**
     * Create a new item.
     *
     * @param $resource
     * @param array $data
     * @param array $params
     *
     * @return Response
     */
    public function post($resource, array $data, array $params = array()): Response;

    /**
     * Update a single item or a list of items.
     *
     * @param $resource
     * @param null  $item
     * @param array $list
     * @param array $params
     *
     * @return Response
     */
    public function put($resource, $item = null, array $list = array(), array $params = array()): Response;

    /**
     * Delete a single item or a list of items.
     *
     * @param $resource
     * @param null  $item
     * @param array $list
     * @param array $params
     *
     * @return Response
     */
    public function delete($resource, $item = null, array $list = array(), array $params = array()): Response;
}
