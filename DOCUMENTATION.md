Shopware Client Documentation
========================
Connection
----------

There is a object which is used to establish the connection to the api.
This object requires the `username`, `key`, `domain` and `apiPath` of your shop.

```php
$connection = new \Shopbase\ShopwareClient\Connection(
    'username',           // Username for Shopware-API
    'key',                // Key for Shopware-API
    'domain',             // Domain to Shop (not including API path)
    'apiPath'             // Path of API
);

...
```

The class for this object is  `\Shopbase\ShopwareClient\Connection`.  
Note that the domain may not include the api-path. 
As exemple your API-Url is 'http://www.example.com/api/' the 
`domain` parameter is `http://www.example.com` and the `apiPath` parameter is `api`.

Client
------
To use the `Connection` for api handling the `\Shopbase\ShopwareClient\Client` class is needed.

```php
...

$client = new \Shopbase\ShopwareClient\Client($connection);

...
```

Whenever the `Client` object is created there is a `Connection` object needed.

Methods
-------

`GET`
___

The `GET` method is used to get a list of items or a single item from the api.
```php
...

$customer_list = $client->get(\Shopbase\ShopwareClient\Resources\Customer::class);     // Returns a complete list of customers
$customer = $client->get(\Shopbase\ShopwareClient\Resources\Customer::class, 15);      // Returns customer with id 15

...
```
___
`POST` / `CREATE`
___

The `POST` method is used to create items with api. This method provides a secound method with named `CREATE` which does the same like `POST`.

```php
post($resource, array $data, array $params = array()): Response
```

Example:
```php
...

$response = $client->post(\Shopbase\ShopwareClient\Resources\Article::class, array(
    'name' => 'Test Article',
    'description' => 'This is a test article',
    'tax' => '19',
    'mainDetail' => array(
        'number' => '1.x.x.x'
    )
));

...
```
___
`PUT` / `UPDATE`
___
The `PUT` method is used to update items with api. This method provides a secound method with named `UPDATE` which does the same like `PUT`.

```php
put($resource, $item = null, array $data = array(), array $params = array()): Response
```
To update an item it is required to pass the `$item` parameter. This will represend the item id.  
It is possible to update a item stack. If you want to update an the `$item` must be `null` and the `$data` must contain an specific data scheme.  
More information's about stack handling you can find into the [Shopware API Documentation](https://developers.shopware.com/developers-guide/rest-api/) 

Example:
```php
...

$response = $client->put(\Shopbase\ShopwareClient\Resources\Article::class, 16, array(
    'name' => 'Updated Test Article',
    'description' => 'This article was updated by api',
));

...
```

___
`DELETE`
___
The `DELETE` method is used to delete items with api.

```php
delete($resource, $item = null, array $list = array(), array $params = array()): Response
```
The parameter scheme will nearly follow the `PUT` method.
The `$list` parameter is used instead of `$data` parameter to delete a stack of items because it is not possible to send data with a delete request.

Example:
```php
...

$response = $client->delete(\Shopbase\ShopwareClient\Resources\Article::class, 16);

...
```

`$resource` Parameter
-------
It is possible to pass two types of values to this parameter. You can pass the class name of a resources like `\Shopbase\ShopwareClient\Resources\Address::class` or an object of resource class like
`new \Shopbase\ShopwareClient\Resources\Address()`.  
It is required to pass a object or classname of a class which implements the `ResourceInterface`. Otherwise the client will return an exception.

`$params` Parameter
-------

All methods will include the `$params` parameter. This parameter is an empty array by default. It can be used to add additional parameters to an api request.  
Example:
```php
...

$article = $client->get(\Shopbase\ShopwareClient\Resources\Article::class, '1.x.x.x', array('useNumberAsId' => true));
// The request will return the article with Number 1.x.x.x

...
```

Stack handling
----
The Shopware supports stack handling for some resources. You can use the stack handling as example to delete a list of articles.  9
Example:
```php
...

$response = $client->delete(\Shopbase\ShopwareClient\Resources\Article::class, null, array(
    array('id' => 16),
    array('mainDetail' => array(
        "number" => "1.x.x.x"
    ))
));

...
```

More informations about stack handling you can find into [Shopware API Documentation](https://developers.shopware.com/developers-guide/rest-api/)

Resources
----

The Shopware API is splitted into several resources like 'Articles, Customers or Media'. Each of them are stored into an own class into this client.  
The default resources are:
```php
\Shopbase\ShopwareClient\Resources\Address::class
\Shopbase\ShopwareClient\Resources\Article::class
\Shopbase\ShopwareClient\Resources\Cache::class
\Shopbase\ShopwareClient\Resources\Categories::class
\Shopbase\ShopwareClient\Resources\Countries::class
\Shopbase\ShopwareClient\Resources\Customer::class
\Shopbase\ShopwareClient\Resources\CustomerGroups::class
\Shopbase\ShopwareClient\Resources\GenerateArticleImage::class
\Shopbase\ShopwareClient\Resources\Manufacturers::class
\Shopbase\ShopwareClient\Resources\Media::class
\Shopbase\ShopwareClient\Resources\Orders::class
\Shopbase\ShopwareClient\Resources\PaymentMethods::class
\Shopbase\ShopwareClient\Resources\PropertyGroups::class
\Shopbase\ShopwareClient\Resources\Shops::class
\Shopbase\ShopwareClient\Resources\Translations::class
\Shopbase\ShopwareClient\Resources\Users::class
\Shopbase\ShopwareClient\Resources\Variants::class
\Shopbase\ShopwareClient\Resources\Version::class
```
List: [Default Shopware API Resources](https://developers.shopware.com/developers-guide/rest-api/#list-of-api-resources)

Register new Resource
-----
Sometimes a Shopware plugin add's a new resource to api. To handle them with this client it is possible to add new resource types.

To add a new resource you need to create a new class which extends the `Shopbase\ShopwareClient\Abstracts\AbstractResource` and implements the `Shopbase\ShopwareClient\Interfaces\ResourceInterface`.
If this class is created you only need to add the configuration of resource.  
Example:
```php
class Coupon extends Shopbase\ShopwareClient\Abstracts\AbstractResource implements Shopbase\ShopwareClient\Interfaces\ResourceInterface
{
    public function __construct()
    {
        $this->setEndpoint('coupons');

        $this->setValidTypes(array(
            Shopbase\ShopwareClient\Types::API_GET_ITEM,
            Shopbase\ShopwareClient\Types::API_GET_LIST,
            Shopbase\ShopwareClient\Types::API_CREATE,
            Shopbase\ShopwareClient\Types::API_UPDATE_ITEM,
            Shopbase\ShopwareClient\Types::API_UPDATE_LIST,
            Shopbase\ShopwareClient\Types::API_DELETE_ITEM,
            Shopbase\ShopwareClient\Types::API_DELETE_LIST,
        ));
    }
}
```

The `setEndpoint` method will declare the endpoint into the api. This endpoint is used by the client to create the request.
Not all resources supports all types of api methods. Sometimes a resource do not support stack handling or updating.   
Which methods are supported you can configure with the `setValidTypes` function.
```php
Shopbase\ShopwareClient\Types::API_GET_ITEM,        // GET list
Shopbase\ShopwareClient\Types::API_GET_LIST,        // GET item
Shopbase\ShopwareClient\Types::API_CREATE,          // POST/CREATE
Shopbase\ShopwareClient\Types::API_UPDATE_ITEM,     // PUT/UPDATE item
Shopbase\ShopwareClient\Types::API_UPDATE_LIST,     // PUT/UPDATE list (stack(
Shopbase\ShopwareClient\Types::API_DELETE_ITEM,     // DELETE item
Shopbase\ShopwareClient\Types::API_DELETE_LIST,     // DELETE list (stack)
```

Response
---

Each method of client will return an `Response` object. This object includes the content and the http-code of api response.
To handle this response the following methods included:

`toJson()` will return the response content as json string
`toArray()` will return the response content as array 
`toObject()` will return the response content as object 
`getResponseCode()` will return the response http code 
