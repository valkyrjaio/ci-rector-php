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

namespace Valkyrja\Rector\Tests\CodingStyle\Rector\Stmt;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveNonConflictingAliasInUseStatementRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/../../../../Fixture/RemoveNonConflictingAliasInUseStatementRector');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/../../../../config/RemoveNonConflictingAliasInUseStatementRector/configured_rule.php';
    }
}