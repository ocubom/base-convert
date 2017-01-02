Big Numbers Base Convert
========================

_Base Convert_ performs safe number conversion between arbitrary bases. The
conversion is performed with the [BC Math Extension][] to avoid native PHP
[base_convert][] function [float precision problem][].

Also provides a safe [Crockford Base32][] encoding/decoding class.

| License | Version | Status | SensioLabsInsight |
| ------- | ------- | ------ | ----------------- |
| [![License](https://poser.pugx.org/ocubom/base-convert/license.svg)][0] | [![Latest Stable Version](https://poser.pugx.org/ocubom/base-convert/v/stable.svg)](https://packagist.org/packages/ocubom/base-convert) [![Latest Unstable Version](https://poser.pugx.org/ocubom/base-convert/v/unstable.svg)](https://packagist.org/packages/ocubom/base-convert) | [![Build Status](https://travis-ci.org/ocubom/base-convert.svg)](https://travis-ci.org/ocubom/base-convert) [![Coverage Status](https://img.shields.io/coveralls/ocubom/base-convert.svg)](https://coveralls.io/r/ocubom/base-convert) | [![SensioLabsInsight](https://insight.sensiolabs.com/projects/5c3634fb-2d32-4986-a4c3-857177d4c07d/big.png)](https://insight.sensiolabs.com/projects/5c3634fb-2d32-4986-a4c3-857177d4c07d) |

Installation
------------

Just use [composer][] to add the dependency:

```
composer require ocubom/base-convert
```

Or add the dependecy manually:

1.  Update ``composer.json`` file with the lines:

    ```
    {
        "require": {
            "ocubom/base-convert": "^1.0.0"
        }
    }
    ```

2.  And update the dependencies:

    ```
    composer update "ocubom/base-convert"
    ```

Authorship
----------

Current maintainer:

* [Oscar Cubo Medina](http://github.com/ocubom/ "@ocubom projects").
  Twitter: [@ocubom](http://twitter.com/ocubom/ "@ocubom on twitter").

Copyright and License
---------------------

_Base Convert_ is licensed under the MIT License — see the [`LICENSE`][0] file
for details.

If you did not receive a copy of the license, contact with the author.


[0]: http://github.com/ocubom/base-convert/blob/master/LICENSE
    "Base Convert License"


[base_convert]: http://php.net/manual/function.base-convert.php
    "BCMath Arbitrary Precision Mathematics"

[BC Math Extension]: http://php.net/manual/book.bc.php
    "BCMath Arbitrary Precision Mathematics"

[Composer]: http://getcomposer.org/
    "Composer Dependency Manager for PHP"

[Crockford Base32]: http://www.crockford.com/wrmg/base32.html
    "Douglas Crockford's Base32 Encoding"

[float precision problem]: http://php.net/manual/en/language.types.float.php
    "Floating point numbers"
