# Omnipay: Laybuy

**Laybuy driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/mediabeastnz/omnipay-laybuy.svg?branch=2.x)](https://travis-ci.org/mediabeastnz/omnipay-laybuy)
[![Latest Stable Version](https://poser.pugx.org/mediabeastnz/omnipay-laybuy/v/stable)](https://packagist.org/packages/mediabeastnz/omnipay-laybuy)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements PayPal support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "mediabeastnz/omnipay-laybuy": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Quirks

Laybuy purchase requests require many fields so be sure to check what fields you'll need to pass through e.g customers phone. Refunds require an orderId so this will need to be stored if you wish to process refunds later.


## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/mediabestnz/omnipay-laybuy/issues),
or better yet, fork the library and submit a pull request.
