<p align="center">
  <a href="https://posthog.com/?utm_source=github&utm_medium=logo" target="_blank">
    <img src="https://posthog.com/brand/posthog-logo-black.png" alt="PostHog" width="157" height="30">
  </a>
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sanderverkuil/posthog-symfony.svg?style=flat-square)](https://packagist.org/packages/sanderverkuil/posthog-symfony)
[![Total Downloads](https://img.shields.io/packagist/dt/sanderverkuil/posthog-symfony.svg?style=flat-square)](https://packagist.org/packages/sanderverkuil/posthog-symfony)

> [!IMPORTANT]
> This repository and project are not backed by [PostHog](https://posthog.com).

# Unofficial PostHog SDK for Symfony

This is the unofficial Symfony SDK for [PostHog](https://posthog.com/).

## Getting Started

Using this `posthog-symfony` SDK provides you with the following benefits:

* Quickly integrate and configure PostHog for your Symfony app
* Out of the box, each event will contain the following data by default
    - The currently authenticated user
    - The Symfony environment

### Install

To install the SDK you will need to be using [Composer]([https://getcomposer.org/)
in your project. To install it please see the [docs](https://getcomposer.org/download/).

```bash
composer require sanderverkuil/posthog-symfony
```

### Enable the Bundle

If you installed the package using the Flex recipe, the bundle will be automatically enabled. Otherwise, enable it by adding it to the list
of registered bundles in the `Kernel.php` file of your project:

```php
class AppKernel extends \Symfony\Component\HttpKernel\Kernel
{
    public function registerBundles(): array
    {
        return [
            // ...
            new \PostHog\PostHogBundle\PostHogBundle(),
        ];
    }

    // ...
}
```

The bundle will be enabled in all environments by default.
To enable event reporting, you'll need to add a key (see the next step).

### Configure

Add the [PostHog key](https://app.posthog.com/products) of your project.
Add the key to your `config/packages/posthog.yaml` file.

Keep in mind that by leaving the `key` value empty (or undeclared), you will disable the PostHog integration:.

```yaml
post_hog:
    key: "<ph_project_api_key>"
    host: "https://app.posthog.com/" # Or https://eu.posthog.com/ for EU
```

#### Optional: use custom HTTP factory/transport

This uses HTTPlug to remain transport-agnostic, you need to install two packages that provide
[`php-http/async-client-implementation`](https://packagist.org/providers/php-http/async-client-implementation)
and [`psr/http-message-implementation`](https://packagist.org/providers/psr/http-message-implementation).

The suggested packages are:
- the Symfony HTTP Client (`symfony/http-client`)
- Guzzle's message factory (`http-interop/http-factory-guzzle`)

## Maintained versions

* 0.x is actively maintained and developed on the master branch, and uses PostHog SDK 3.0;

## Contributing to the SDK

Please refer to [CONTRIBUTING.md](CONTRIBUTING.md).

## License

Licensed under the MIT license, see [`LICENSE`](LICENSE)

### Attributions

- I would like to thank [@getsentry](https://github.com/getsentry) for inspiration on how to set up a symfony bundle with regards to general things.
- I would like to thank [@posthog](https://github.com/posthog) for their product.
