<p align="center"><a href="https://valkyrja.io" target="_blank">
    <img src="https://raw.githubusercontent.com/valkyrjaio/art/refs/heads/master/long-banner/orange/php.png" width="100%">
</a></p>

# Valkyrja Rector

Shared Rector configuration for Valkyrja PHP projects — a reusable rule set,
custom Rector rules, and a workflow that enforce consistent automated
refactoring across consuming repositories.

<p>
    <a href="https://packagist.org/packages/valkyrja/ci-rector"><img src="https://poser.pugx.org/valkyrja/ci-rector/require/php" alt="PHP Version Require"></a>
    <a href="https://packagist.org/packages/valkyrja/ci-rector"><img src="https://poser.pugx.org/valkyrja/ci-rector/v" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/valkyrja/ci-rector"><img src="https://poser.pugx.org/valkyrja/ci-rector/license" alt="License"></a>
    <a href="https://github.com/valkyrjaio/ci-rector-php/actions/workflows/ci.yml?query=branch%3A26.x"><img src="https://github.com/valkyrjaio/ci-rector-php/actions/workflows/ci.yml/badge.svg?branch=26.x" alt="CI Status"></a>
    <a href="https://scrutinizer-ci.com/g/valkyrjaio/ci-rector-php/?branch=26.x"><img src="https://scrutinizer-ci.com/g/valkyrjaio/ci-rector-php/badges/quality-score.png?b=26.x" alt="Scrutinizer"></a>
    <a href="https://coveralls.io/github/valkyrjaio/ci-rector-php?branch=26.x"><img src="https://coveralls.io/repos/github/valkyrjaio/ci-rector-php/badge.svg?branch=26.x" alt="Coverage Status" /></a>
    <a href="https://shepherd.dev/github/valkyrjaio/ci-rector-php"><img src="https://shepherd.dev/github/valkyrjaio/ci-rector-php/coverage.svg" alt="Psalm Shepherd" /></a>
    <a href="https://sonarcloud.io/summary/new_code?id=valkyrjaio_rector"><img src="https://sonarcloud.io/api/project_badges/measure?project=valkyrjaio_rector&metric=sqale_rating" alt="Maintainability Rating" /></a>
</p>

Overview
--------

This repository contains two things:

1. **`Valkyrja\Rector\Rules`** — a reusable `RectorConfigBuilder` factory
   that wires up all rules used across the Valkyrja monorepo. Drop it into
   any `rector.php` config and set your paths on the returned builder.
2. **Custom Rector rules** — project-specific refactoring rules that fill
   gaps in the built-in Rector library.

Installation
------------

```
composer require valkyrja/ci-rector
```

Usage
-----

Call `Rules::getConfig()` from your `rector.php` configuration file and set
the source paths on the returned builder:

```
// rector.php
use Valkyrja\Rector\Rules;

return Rules::getConfig()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
```

`getConfig()` takes no arguments and returns a `RectorConfigBuilder`. Chain
any additional Rector configuration — paths, skip rules, PHP version sets,
etc. — directly on the returned builder before returning it.

Run Rector as normal:

```
vendor/bin/rector process
```

Or in dry-run mode to preview changes without writing them:

```
vendor/bin/rector process --dry-run
```

Configuration Details
---------------------

### Parallel Execution

`withParallel()` is enabled, allowing Rector to process files concurrently
across multiple worker processes.

### Import Names

`withImportNames(removeUnusedImports: true)` is set, so Rector will:

- Add fully-qualified `use` imports for any names that aren't already
  imported
- Remove `use` statements that are no longer referenced

### Rules

#### Built-in Rules

| Rule                                            | Description                                                                                      |
| ----------------------------------------------- | ------------------------------------------------------------------------------------------------ |
| `AddVoidReturnTypeWhereNoReturnRector`          | Adds `: void` return type to methods that have no `return` statement                             |
| `AddOverrideAttributeToOverriddenMethodsRector` | Adds `#[Override]` attribute to methods that override a parent                                   |
| `ConvertStaticToSelfRector`                     | Converts `static::` to `self::` inside non-final classes where late static binding is not needed |
| `ExplicitNullableParamTypeRector`               | Converts `?Type` param declarations to `Type\|null` union syntax                                 |
| `NewMethodCallWithoutParenthesesRector`         | Removes unnecessary parentheses from `new` expressions used directly in method chains (PHP 8.4)  |
| `RemoveParentCallWithoutParentRector`           | Removes `parent::method()` calls when no parent class exists                                     |
| `RemoveUselessAliasInUseStatementRector`        | Removes aliases in `use` statements where the alias matches the class's own short name           |
| `RemoveUselessParamTagRector`                   | Removes `@param` PHPDoc tags whose type is already expressed in the signature                    |
| `RemoveUselessReturnTagRector`                  | Removes `@return` PHPDoc tags whose type is already expressed in the signature                   |
| `SeparateMultiUseImportsRector`                 | Splits `use A, B;` into individual `use A; use B;` statements                                    |
| `StaticToSelfOnFinalClassRector`                | Converts `static::` to `self::` inside final classes                                             |

#### Custom Rules

##### `RemoveNonConflictingAliasInUseStatementRector`

`Valkyrja\Rector\CodingStyle\Rector\Stmt\RemoveNonConflictingAliasInUseStatementRector`

Removes aliases from `use` statements when the alias serves no purpose —
i.e. when nothing in the file would conflict if the alias were dropped.

The built-in `RemoveUselessAliasInUseStatementRector` only removes an alias
when it is identical to the imported class's short name. This rule goes
further: it removes the alias whenever keeping it is unnecessary, checking
for conflicts against:

- Other `use` statements in the file (by class name and by alias)
- The file's own class, interface, trait, or enum name

When the alias is removed, all references to it throughout the file —
including PHPDoc comments, type declarations, `extends`, `implements`, and
`use` (traits) — are rewritten to the unaliased short name.

**Before:**

```
use App\Bar as AppBar;

class Foo
{
    public function baz(AppBar $bar): void {}
}
```

**After:**

```
use App\Bar;

class Foo
{
    public function baz(Bar $bar): void {}
}
```

An alias is preserved whenever it would cause a naming conflict — for
example when two imports share the same short name, or when the short name
clashes with the file's own class name.

Workflows
---------

The [`_workflow-call.yml`](.github/workflows/_workflow-call.yml) reusable
workflow runs Rector against the calling repository's source. It is
designed to be called from other repositories via `workflow_call`.

### Inputs

| Input              | Type    | Default               | Description                                                                                                                                           |
| ------------------ | ------- | --------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------- |
| `paths`            | string  | —                     | **Required.** YAML filter spec with two keys: `ci` (CI config files that trigger a base-branch fetch) and `files` (all files that trigger the check). |
| `post-pr-comment`  | boolean | `true`                | Post a PR comment on failure and remove it on success. Disable when the calling workflow handles its own reporting.                                   |
| `composer-options` | string  | `''`                  | Extra flags passed to every `composer install` step (e.g. `--ignore-platform-req=ext-openswoole`).                                                    |
| `php-version`      | string  | `'8.4'`               | PHP version to use.                                                                                                                                   |
| `ci-directory`     | string  | `'.github/ci/rector'` | Path to the CI directory containing `composer.json` and the tool config.                                                                              |
| `extensions`       | string  | `'mbstring, intl'`    | PHP extensions to install via `shivammathur/setup-php`.                                                                                               |

### Usage

```yaml
jobs:
  rector:
    uses: valkyrjaio/ci-rector-php/.github/workflows/_workflow-call.yml@26.x
    permissions:
      pull-requests: write
      contents: read
    with:
      php-version: '8.4'
      paths: |
        ci:
          - '.github/ci/rector/**'
          - '.github/workflows/rector.yml'
        files:
          - '.github/ci/rector/**'
          - '.github/workflows/rector.yml'
          - 'src/**/*.php'
          - 'composer.json'
    secrets: inherit
```

`secrets: inherit` is required to pass the `VALKYRJA_GHA_APP_ID` and
`VALKYRJA_GHA_PRIVATE_KEY` org secrets used for PR comments.

Contributing
------------

See [`CONTRIBUTING.md`][contributing url] for the submission process and
[`VOCABULARY.md`][vocabulary url] for the terminology used across Valkyrja.

Security Issues
---------------

If you discover a security vulnerability, please follow our
[disclosure procedure][security vulnerabilities url].

License
-------

Licensed under the [MIT license][MIT license url]. See
[`LICENSE.md`](./LICENSE.md).

[contributing url]: https://github.com/valkyrjaio/.github/blob/26.x/CONTRIBUTING.md
[vocabulary url]: https://github.com/valkyrjaio/.github/blob/26.x/VOCABULARY.md
[security vulnerabilities url]: https://github.com/valkyrjaio/.github/blob/26.x/SECURITY.md
[MIT license url]: https://opensource.org/licenses/MIT
