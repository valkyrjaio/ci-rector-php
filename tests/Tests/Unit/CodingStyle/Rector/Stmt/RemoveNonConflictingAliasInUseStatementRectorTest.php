<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Rector\Tests\Unit\CodingStyle\Rector\Stmt;

use PhpParser\Comment;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\UseItem;
use Rector\PhpParser\Node\FileNode;
use stdClass;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Valkyrja\Rector\CodingStyle\Rector\Stmt\RemoveNonConflictingAliasInUseStatementRector;
use Valkyrja\Rector\Tests\Abstract\RectorTestCase;

/**
 * Unit tests covering branches of the rule that are not reachable through the
 * fixture-driven integration test (defensive guards and degenerate nodes).
 */
final class RemoveNonConflictingAliasInUseStatementRectorTest extends RectorTestCase
{
    private RemoveNonConflictingAliasInUseStatementRector $rule;

    protected function setUp(): void
    {
        $this->rule = new RemoveNonConflictingAliasInUseStatementRector();
    }

    public function testGetRuleDefinitionReturnsDefinition(): void
    {
        self::assertInstanceOf(RuleDefinition::class, $this->rule->getRuleDefinition());
    }

    public function testGetNodeTypes(): void
    {
        self::assertSame([FileNode::class, Namespace_::class], $this->rule->getNodeTypes());
    }

    public function testRefactorReturnsNullForUnsupportedNode(): void
    {
        // Neither FileNode nor Namespace_ -> the defensive else branch.
        self::assertNull($this->rule->refactor(new Nop()));
    }

    public function testRefactorSkipsUseStatementWithMultipleUses(): void
    {
        $use       = new Use_([new UseItem(new Name('App\\A')), new UseItem(new Name('App\\B'))]);
        $namespace = new Namespace_(new Name('App'), [$use]);

        self::assertNull($this->rule->refactor($namespace));
    }

    public function testRefactorSkipsUseStatementWithoutZeroIndex(): void
    {
        $use = new Use_([new UseItem(new Name('App\\A'), new Identifier('AppA'))]);
        // Single use, but not at index 0 -> the defensive isset guard.
        $use->uses = [1 => $use->uses[0]];

        $namespace = new Namespace_(new Name('App'), [$use]);

        self::assertNull($this->rule->refactor($namespace));
    }

    public function testRefactorSkipsComparedUseStatementWithMultipleUses(): void
    {
        $aliased   = new Use_([new UseItem(new Name('App\\Bar'), new Identifier('AppBar'))]);
        $multi     = new Use_([new UseItem(new Name('App\\X')), new UseItem(new Name('App\\Y'))]);
        $namespace = new Namespace_(new Name('App'), [$aliased, $multi]);

        $result = $this->rule->refactor($namespace);

        self::assertInstanceOf(Namespace_::class, $result);
        self::assertNull($aliased->uses[0]->alias);
    }

    public function testRefactorSkipsComparedUseStatementWithoutZeroIndex(): void
    {
        $aliased     = new Use_([new UseItem(new Name('App\\Bar'), new Identifier('AppBar'))]);
        $weird       = new Use_([new UseItem(new Name('App\\X'), new Identifier('AppX'))]);
        $weird->uses = [1 => $weird->uses[0]];

        $namespace = new Namespace_(new Name('App'), [$aliased, $weird]);

        $result = $this->rule->refactor($namespace);

        self::assertInstanceOf(Namespace_::class, $result);
        self::assertNull($aliased->uses[0]->alias);
    }

    public function testModifyCommentsSkipsNonCommentsAndKeepsNonMatching(): void
    {
        $aliased = new Use_([new UseItem(new Name('App\\Bar'), new Identifier('AppBar'))]);
        $class   = new Class_('Foo');
        // A non-Comment entry (skipped) and a non-matching Comment (kept as-is).
        $class->setAttribute('comments', [new stdClass(), new Comment('// unrelated')]);

        $namespace = new Namespace_(new Name('App'), [$aliased, $class]);

        $result = $this->rule->refactor($namespace);

        self::assertInstanceOf(Namespace_::class, $result);
        self::assertNull($aliased->uses[0]->alias);
    }
}
