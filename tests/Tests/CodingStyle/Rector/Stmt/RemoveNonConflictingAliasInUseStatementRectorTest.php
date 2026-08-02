<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Rector\Tests\CodingStyle\Rector\Stmt;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveNonConflictingAliasInUseStatementRectorTest extends AbstractRectorTestCase
{
    public static function provideData(): Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/../../../../Fixture/RemoveNonConflictingAliasInUseStatementRector');
    }

    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/../../../../config/RemoveNonConflictingAliasInUseStatementRector/configured_rule.php';
    }
}
