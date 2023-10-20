<p align="center">
  <a href="https://posthog.com/?utm_source=github&utm_medium=logo" target="_blank">
    <img src="https://posthog.com/brand/posthog-logo-black.png" alt="PostHog" width="157" height="30">
  </a>
</p>

> [!IMPORTANT]
> This repository and project are not backed by [PostHog](https://posthog.com).

# Contributing to the PostHog SDK for Symfony

We welcome contributions to `posthog-symfony` by the community.

Please search the [issue tracker](https://github.com/sanderverkuil/posthog-symfony/issues) before creating a new issue (a problem or an improvement request).

If you feel that you can fix or implement it yourself, please read on to learn how to submit your changes.

## Submitting changes

- Setup the development environment.
- Clone the `posthog-symfony` repository and prepare necessary changes.
- Add tests for your changes to `tests/`.
- Run tests and make sure all of them pass.
- Submit a pull request, referencing any issues it addresses.

We will review your pull request as soon as possible.
Thank you for contributing!

## Development environment

### Clone the repository

```bash
git clone git@github.com:sanderverkuil/posthog-symfony.git
```

Make sure that you have PHP 8.0+ installed. On macOS, we recommend using brew to install PHP. For Windows, we recommend an official PHP.net release.

### Install the dependencies

Dependencies are managed through [Composer](https://getcomposer.org/):

```bash
composer install
```

### Running tests

Tests can then be run via [PHPUnit](https://phpunit.de/):

```bash
vendor/bin/phpunit
```

## Releasing a new version

(only relevant for maintainers)

Prerequisites:

- All changes that should be released must be in the `main` branch.
- Every commit should follow the [Conventional Commits 1.0.0](https://www.conventionalcommits.org/en/v1.0.0/) guide.

Manual Process:

- Update CHANGELOG.md with the version to be released.
- On GitHub in the `posthog-symfony` repository go to "Actions" select the "Release" workflow.
- Click on "Run workflow" on the right side and make sure the `main` branch is selected.
- Set "Version to release" input field. Here you decide if it is a major, minor or patch release. (See "Versioning Policy" below)
- Click "Run Workflow"

### Versioning Policy

This project follows [semver](https://semver.org/), with three additions:

- Semver says that major version `0` can include breaking changes at any time. Still, it is common practice to assume that only `0.x` releases (minor versions) can contain breaking changes while `0.x.y` releases (patch versions) are used for backwards-compatible changes (bugfixes and features). This project also follows that practice.

- All undocumented APIs are considered internal. They are not part of this contract.

We recommend pinning your version requirements against `1.x.*` or `1.x.y`.
Either one of the following is fine:

```json
{
  "posthog/posthog-php": "^1.0",
  "posthog/posthog-php": "^1"
}
```

A major release `N` implies the previous release `N-1` will no longer receive updates. We generally do not backport bugfixes to older versions unless they are security relevant. However, feel free to ask for backports of specific commits on the bug tracker.
