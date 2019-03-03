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

namespace Shopbase\ShopwareClient\Resources;

use Shopbase\ShopwareClient\Abstracts\AbstractResource;
use Shopbase\ShopwareClient\Interfaces\ResourceInterface;
use Shopbase\ShopwareClient\Types;

class PaymentMethods extends AbstractResource implements ResourceInterface
{
    public function __construct()
    {
        $this->setEndpoint('paymentMethods');

        $this->setValidTypes(array(
            Types::API_GET_ITEM,
            Types::API_GET_LIST,
            Types::API_CREATE,
            Types::API_UPDATE_ITEM,
            Types::API_DELETE_ITEM,
        ));
    }
}
