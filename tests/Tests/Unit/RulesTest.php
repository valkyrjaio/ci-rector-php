<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Rector\Tests\Unit;

use FilesystemIterator;
use Rector\CodeQuality\Rector\Class_\ConvertStaticToSelfRector;
use Rector\CodingStyle\Rector\Stmt\RemoveUselessAliasInUseStatementRector;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Configuration\RectorConfigBuilder;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector;
use Rector\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector;
use Rector\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionProperty;
use Valkyrja\Rector\CodingStyle\Rector\Stmt\RemoveNonConflictingAliasInUseStatementRector;
use Valkyrja\Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Valkyrja\Rector\Rules;
use Valkyrja\Rector\Tests\Abstract\RectorTestCase;

use function dirname;
use function is_dir;
use function md5;
use function rmdir;
use function sys_get_temp_dir;
use function unlink;

final class RulesTest extends RectorTestCase
{
    /**
     * Remove a directory and everything below it.
     */
    private static function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $paths = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($paths as $path) {
            if ($path->isDir()) {
                rmdir($path->getPathname());

                continue;
            }

            unlink($path->getPathname());
        }

        rmdir($directory);
    }

    public function testGetConfigReturnsConfigBuilder(): void
    {
        self::assertInstanceOf(RectorConfigBuilder::class, Rules::getConfig());
    }

    public function testGetConfigConfiguresExactlyTheseRules(): void
    {
        $rules = new ReflectionProperty(RectorConfigBuilder::class, 'rules')->getValue(Rules::getConfig());

        self::assertSame(
            [
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
            ],
            $rules
        );
    }

    public function testGetConfigCreatesACacheDirectoryThatBelongsToOneProject(): void
    {
        $base = sys_get_temp_dir() . '/valkyrja-rector/' . md5(dirname(new ReflectionClass(Rules::class)->getFileName() ?: ''));

        self::removeDirectory($base);

        Rules::getConfig();

        self::assertDirectoryExists($base . '/files');
        self::assertDirectoryExists($base . '/container');
    }

    public function testGetConfigKeepsACacheDirectoryThatExists(): void
    {
        // The second call takes the branch that finds the directory already there.
        Rules::getConfig();
        Rules::getConfig();

        $base = sys_get_temp_dir() . '/valkyrja-rector/' . md5(dirname(new ReflectionClass(Rules::class)->getFileName() ?: ''));

        self::assertDirectoryExists($base . '/files');
        self::assertDirectoryExists($base . '/container');
    }
}
