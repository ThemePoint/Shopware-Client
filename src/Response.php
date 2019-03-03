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

final class Response
{
    private $data;

    private $code;

    public function __construct($result, $code)
    {
        $this->data = $result;
        $this->code = $code;
    }

    public function getResponseCode()
    {
        return $this->code;
    }

    public function toJson(): string
    {
        return $this->data;
    }

    public function toArray(callable $mapping = null): array
    {
        $result = json_decode($this->data, true);

        if ($mapping !== null && \is_callable($mapping)) {
            $result = $mapping($result);
        }

        return $result;
    }

    public function toObject(callable $mapping = null)
    {
        $result = json_decode($this->data, true);

        if ($mapping !== null && \is_callable($mapping)) {
            $result = $mapping($result);
        }

        return $result;
    }
}
