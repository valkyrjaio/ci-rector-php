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

namespace Valkyrja\Rector\Tests\Unit;

use FilesystemIterator;
use Rector\Configuration\RectorConfigBuilder;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
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
