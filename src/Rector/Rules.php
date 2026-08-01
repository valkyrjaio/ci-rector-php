<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Rector;

use Rector\CodeQuality\Rector\Class_\ConvertStaticToSelfRector;
use Rector\CodingStyle\Rector\Stmt\RemoveUselessAliasInUseStatementRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Config\RectorConfig;
use Rector\Configuration\RectorConfigBuilder;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector;
use Rector\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Valkyrja\Rector\CodingStyle\Rector\Stmt\RemoveNonConflictingAliasInUseStatementRector;

use function is_dir;
use function md5;
use function mkdir;
use function sys_get_temp_dir;

class Rules
{
    public static function getConfig(): RectorConfigBuilder
    {
        $rector = RectorConfig::configure();

        return $rector
            ->withCache(
                cacheDirectory: self::getCacheDirectory('files'),
                containerCacheDirectory: self::getCacheDirectory('container')
            )
            ->withParallel()
            ->withImportNames(removeUnusedImports: true)
            ->withRules([
                AddVoidReturnTypeWhereNoReturnRector::class,
                AddOverrideAttributeToOverriddenMethodsRector::class,
                ConvertStaticToSelfRector::class,
                ExplicitNullableParamTypeRector::class,
                NewMethodCallWithoutParenthesesRector::class,
                RemoveNonConflictingAliasInUseStatementRector::class,
                RemoveParentCallWithoutParentRector::class,
                RemoveUselessAliasInUseStatementRector::class,
                RemoveUselessParamTagRector::class,
                RemoveUselessReturnTagRector::class,
                SeparateMultiUseImportsRector::class,
                StaticToSelfOnFinalClassRector::class,
            ]);
    }

    /**
     * Get a cache directory that belongs to one project.
     *
     * Rector defaults every project on a machine to one directory in the system temp directory.
     * The compiled container caches an absolute path into the `vendor` directory of the project
     * that compiled it, and that path reaches inside `phpstan.phar`. A second project then loads a
     * container that points at a directory it never analyzed. The failure appears when the first
     * project moves or goes away: Rector reports that a file inside a `phar` "is not a file", and
     * it names a directory that belongs to another repository. A git worktree makes this common,
     * because a developer removes a worktree when the work ends.
     *
     * The directory name comes from `__DIR__`, because Composer installs this package into the
     * `vendor` directory of each project. The path therefore identifies one project, and it stays
     * the same when the developer runs Rector from another directory.
     *
     * @param non-empty-string $kind The kind of cache the directory holds
     */
    private static function getCacheDirectory(string $kind): string
    {
        $directory = sys_get_temp_dir() . '/valkyrja-rector/' . md5(__DIR__) . '/' . $kind;

        // Rector requires the container cache directory to exist before it builds the container.
        if (! is_dir($directory)) {
            mkdir(directory: $directory, recursive: true);
        }

        return $directory;
    }
}
