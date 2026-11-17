<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Rector\Tests\Unit\Php83\Rector\ClassMethod;

use PhpParser\Node\Stmt\Nop;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Valkyrja\Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;

final class AddOverrideAttributeToOverriddenMethodsRectorTest extends AbstractLazyTestCase
{
    public function testGetRuleDefinitionDescribesBothOptions(): void
    {
        $rule       = $this->make(AddOverrideAttributeToOverriddenMethodsRector::class);
        $definition = $rule->getRuleDefinition();

        self::assertSame('Add override attribute to overridden methods', $definition->getDescription());
        self::assertCount(2, $definition->getCodeSamples());
    }

    public function testRefactorReturnsNullForANodeThatIsNotAClass(): void
    {
        $rule = $this->make(AddOverrideAttributeToOverriddenMethodsRector::class);

        self::assertNull($rule->refactor(new Nop()));
    }
}
