Big Numbers Base Convert
========================

_Base Convert_ performs safe number conversion between arbitrary bases. The
conversion is performed with the [BC Math Extension][] to avoid native PHP
[base_convert][] function [float precision problem][].

Also provides a safe [Crockford Base32][] encoding/decoding class.

Installation
------------

Just use [composer][] to add the dependency:

```
composer require ocubom/base-convert:dev-master
```

Or add the dependecy manually:

1.  Update ``composer.json`` file with the lines:

    ```
    {
        "require": {
            "ocubom/base-convert": "dev-master"
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
