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

namespace Shopbase\ShopwareClient\Abstracts;

use Shopbase\ShopwareClient\Exceptions\ResourceException;
use Shopbase\ShopwareClient\Interfaces\ResourceInterface;
use Shopbase\ShopwareClient\Types;

abstract class AbstractResource implements ResourceInterface
{
    private $endpoint;

    private $validTypes = array();

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setValidType(string $type): void
    {
        if (!\in_array($type, Types::getValidApiMethods())) {
            throw new ResourceException(sprintf('Method type %s is not valid', $type));
        }

        array_push($this->validTypes, $type);
    }

    public function setValidTypes(array $validTypes): void
    {
        foreach ($validTypes as $type) {
            $this->setValidType($type);
        }
    }

    public function getValidTypes(): array
    {
        return $this->validTypes;
    }
}
