<div align="center">

Big Numbers Base Convert
========================

Safe number conversion between arbitrary bases.

[![Contributors][contributors-img]][contributors-url]
[![Forks][forks-img]][forks-url]
[![Stargazers][stars-img]][stars-url]
[![Issues][issues-img]][issues-url]
[![License][license-img]][license-url]
[![Version][packagist-img]][packagist-url]
[![CI][workflow-ci-img]][workflow-ci-url]
[![Coverage][coverage-ci-img]][coverage-ci-url]

[**Explore the docs »**](https://github.com/ocubom/base-convert)

[Report Bug](https://github.com/ocubom/base-convert/issues)
·
[Request Feature](https://github.com/ocubom/base-convert/issues)

</div>

<details>
  <summary>Contents</summary>

* [About](#about-base-convert)
* [Getting Started](#getting-started)
    * [Installation](#installation)
    * [Usage](#usage)
* [Roadmap](#roadmap)
* [Contributing](#contributing)
* [Authorship](#authorship)
* [License](#license)

</details>

## About Base Convert

[Base Convert](https://github.com/ocubom/base-convert) performs safe number conversion between arbitrary bases.
The conversion is performed with a custom implementation to avoid native PHP [base_convert][] function [float precision problem][].

The implementation used is extracted from the [Symfony UID component][].
The class `BinaryUtil` implements the necessary `fromBase` and `toBase` methods.
These methods have been extracted to ensure compatibility (it is an internal class) and to reduce the minimum PHP version requirement.

## Getting Started

### Installation

Make sure [Composer][] is installed globally, as explained in the [installation chapter](https://getcomposer.org/doc/00-intro.md) of the Composer documentation.

```console
$ composer require ocubom/base-convert
```

### Usage

Just use like the native [base_convert][].
Although the implementation is compatible with native conversion it provides some improvements:

* Bases can be between 2 and 62.
  The native version only supports bases between 2 and 36.

  The conversion uses [Base62][] in the same manner as the [GMP extension][].
  The conversions are compatible.

* "Named bases" are supported. 

  | Name  |  Base   |
  |:-------:|:-------:|
  | `dec` | base-10 |
  | `hex` | base-16 |
  | `oct` | base-8  |

* The special base `bin` can be used to convert from/to binary strings.
  This can be used to directly convert binary outputs of some functions.

  ```php
  use function \Ocubom\Math\base_convert;
  
  $hex = base_convert(random_bytes(32), 'bin', 'hex');
  ```
  
  > **Warning**
  >
  > Do not confuse a binary encoding (`bin`) with the Base2 encoding (a text string with the characters `0` and `1`).
  > 
  > In PHP there are several functions:
  > 
  > * [bin2hex][]/[hex2bin][] that convert between hexadecimal and binary.
  > 
  > * [bindec][]/[decbin][] that convert between Base2 and Base10.
  > 
  > In this library the string `bin` has been considered to be equivalent to the first set of functions.


* New bases can be used by implementing the base interface.

  Includes bases for Douglas Crockford Base32 and Satoshi Nakamoto Base58 encodings.
  The implementations are examples of how to customize your own encoding.

#### [Douglas Crockford Base 32 encoding](https://www.crockford.com/base32.html)

A secure version of the Base 32 encoding proposed by Douglas Crockford.

> **Note**
>
> The encoding scheme is required to
>
> * Be human-readable and machine-readable.
>
> * Be compact.
>   Humans have difficulty in manipulating long strings of arbitrary symbols.
>
> * Be error resistant.
>   Entering the symbols must not require keyboarding gymnastics.
>
> * Be pronounceable.
>   Humans should be able to accurately transmit the symbols to other humans using a telephone.
>
> -- Douglas Crockford. [Base 32](https://www.crockford.com/base32.html)

This encoding is accessible:

* By passing a `Crockford` object as any of the base arguments of the `base_convert` function.

  ```php
  use Ocubom\Math\Base\Crockford;
  use function Ocubom\Math\base_convert;
  
  // Encoding
  $crockford = base_convert(random_bytes(32), 'bin', new Crockford());

  // Decoding
  $hex = base_convert($crockford, new Crockford(), 'hex');
  ```

* Using the `encode` and `decode` methods of the `Crockford` class.

  ```php
  use Ocubom\Math\Crockford;
  
  // Encoding
  $crockford = Crockford::encode(random_bytes(32), 'bin');

  // Decoding
  $hex = Crockford::decode($crockford, 'hex');
  ```

* Using the `crockford_encode` and `crockford_decode` functions.

  ```php
  use function \Ocubom\Math\crockford_decode;
  use function \Ocubom\Math\crockford_encode;
  
  // Encoding
  $crockford = crockford_encode(random_bytes(32), 'bin');

  // Decoding
  $hex = crockford_decode($crockford, 'hex');
  ```

An additional parameter can be added to include a checksum for error detection.

#### Satoshi Nakamoto Base 58 encoding

Bitcoin addresses use a variant of Base 64 that omits characters that can lead to confusion.
The result is a Base 58 encoding.

> **Note**
> 
> Why base-58 instead of standard base-64 encoding?
> 
> * Don't want 0OIl characters that look the same in some fonts and could be used to create visually identical looking data.
>
> * A string with non-alphanumeric characters is not as easily accepted as input.
>
> * E-mail usually won't line-break if there's no punctuation to break at.
>
> * Double-clicking selects the whole string as one word if it's all alphanumeric.
>
> -- Satoshi Nakamoto. [Bitcoin source code](https://github.com/bitcoin/bitcoin/blob/v23.0/src/base58.h#L7-L12)

This encoding is accessible by passing a `Base58` object as any of the base arguments of the `base_convert` function.

  ```php
  use Ocubom\Math\Base\Base58;
  use function Ocubom\Math\base_convert;
  
  // Encoding
  $base58 = base_convert(random_bytes(32), 'bin', new Base58());

  // Decoding
  $hex = base_convert($base58, new Base58(), 'hex');
  ```

## Roadmap

See the [open issues](https://github.com/ocubom/base-convert/issues) for a full list of proposed features (and known issues).

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create.
Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request.
You can also simply open an issue with the tag "enhancement".

1. Fork the Project.
2. Create your Feature Branch (`git checkout -b feature/your-feature`).
3. Commit your Changes (`git commit -m 'Add your-feature'`).
4. Push to the Branch (`git push origin feature/your-feature`).
5. Open a Pull Request.

## Authorship

* Oscar Cubo Medina — [@ocubom](https://twitter.com/ocubom) — https://ocubom.github.io

See also the list of [contributors][contributors-url] who participated in this project.

## License

Distributed under the MIT License.
See [LICENSE][] for more information.


[LICENSE]: https://github.com/ocubom/base-convert/blob/master/LICENSE


<!-- Links -->

[Base62]: https://wikipedia.org/wiki/Base62

[base_convert]: https://www.php.net/manual/function.base-convert.php
    "PHP base_convert"

[Composer]: http://getcomposer.org/

[Crockford Base32]: http://www.crockford.com/wrmg/base32.html
    "Douglas Crockford's Base32 Encoding"

[float precision problem]: http://php.net/manual/language.types.float.php
    "PHP Floating point numbers"

[GMP extension]: https://www.php.net/manual/book.gmp.php
    "PHP GMP extension"

[bin2hex]: https://www.php.net/manual/function.bin2hex.php
    "PHP bin2hex"

[hex2bin]: https://www.php.net/manual/function.hex2bin.php
"PHP hex2bin"

[bindec]: https://www.php.net/manual/function.bindec.php
    "PHP bindec"

[decbin]: https://www.php.net/manual/function.decbin.php
    "PHP decbin"

[Symfony UID component]: https://symfony.com/doc/current/components/uid.html

<!-- Project Badges -->
[contributors-img]: https://img.shields.io/github/contributors/ocubom/base-convert.svg?style=for-the-badge
[contributors-url]: https://github.com/ocubom/base-convert/graphs/contributors
[forks-img]:        https://img.shields.io/github/forks/ocubom/base-convert.svg?style=for-the-badge
[forks-url]:        https://github.com/ocubom/base-convert/network/members
[stars-img]:        https://img.shields.io/github/stars/ocubom/base-convert.svg?style=for-the-badge
[stars-url]:        https://github.com/ocubom/base-convert/stargazers
[issues-img]:       https://img.shields.io/github/issues/ocubom/base-convert.svg?style=for-the-badge
[issues-url]:       https://github.com/ocubom/base-convert/issues
[license-img]:      https://img.shields.io/github/license/ocubom/base-convert.svg?style=for-the-badge
[license-url]:      https://github.com/ocubom/base-convert/blob/master/LICENSE
[workflow-ci-img]:  https://img.shields.io/github/workflow/status/ocubom/base-convert/test.svg?label=CI&logo=github&style=for-the-badge
[workflow-ci-url]:  https://github.com/ocubom/base-convert/actions/
[coverage-ci-img]:  https://img.shields.io/codecov/c/github/ocubom/base-convert.svg?logo=codecov&logoColor=%23fefefe&style=for-the-badge&token=NQOE5BY6MX
[coverage-ci-url]:  https://app.codecov.io/gh/ocubom/base-convert
[packagist-img]:    https://img.shields.io/packagist/v/ocubom/base-convert.svg?logo=packagist&logoColor=%23fefefe&style=for-the-badge
[packagist-url]:    https://packagist.org/packages/ocubom/base-convert
