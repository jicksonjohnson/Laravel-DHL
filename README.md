# Laravel DHL Wrap

Laravel DHL module works with XML service 

## Getting Started

### Prerequisites

This is build for Laravel 5.6.

### Installing

```
composer require jickson/laravel-dhl-api
```

Since Laravel 5.5 automatically includes the service provider, it won't be necessary to register it. However, if you really want to, run the following command

```

```

##Usage Examples

###Capability

This is typically used to test the validity of addresses and DHL's capability to deliver. Validate must return `true`.

```
$user = User::first();

$GetCapability = new \Jickson\DHL\API\GetCapability();
$GetCapability->user($user);
dd($GetCapability->validate());
```

Dump the request
```
dump($GetCapability->toXML());
```

Dump the response
```
dump($GetCapability->doCurlPost());
dump($GetCapability->requestRAW());
```

###Quotation

This is used to get product information such as the price and total transit days.

```
$product = [];
foreach ($cart->items as $key => $cartItem) {
    for ($i = 1; $i <= $cartItem->quantity; $i++) {
        $product[ $key ]['height'] = $box['height'];
        $product[ $key ]['depth'] = $box['length'];
        $product[ $key ]['width'] = $box['width'];
        $product[ $key ]['weight'] = $cartItem->warehouse->product->weight + $box1['weight'];
    }
}
```

```
$GetQuote = new \Jickson\DHL\API\GetQuote();
$GetQuote->user($user)
    ->reference($cart->order->reference)
    ->addProduct($product)
    ->declaredValue($cart->subtotal);
    
$result = $GetQuote
    ->doCurlPost();
    
dd($result);
```

Dump the request
```
dd($GetQuote->toXML());
```

Dump the response
```
dump($GetQuote->results());
dump($GetQuote->resultsRAW());
```

## Authors

* **Jickson Johnson** - [https://github.com/Jickson]

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Thanks Duwayne Brown for providing tips and base for this application.