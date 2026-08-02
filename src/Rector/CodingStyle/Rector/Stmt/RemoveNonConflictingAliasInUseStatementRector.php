<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Rector\CodingStyle\Rector\Stmt;

use Nette\Utils\Strings;
use Override;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeFinder;
use Rector\PhpParser\Node\FileNode;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Exception\PoorDocumentationException;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

use function array_filter;
use function count;
use function is_array;
use function preg_match;
use function preg_replace;
use function strtolower;

use const false;
use const true;

final class RemoveNonConflictingAliasInUseStatementRector extends AbstractRector
{
    /**
     * @throws PoorDocumentationException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove non-conflicting alias in use statement when an alias exists for no conflicting reason', [
            new CodeSample(
                <<<'CODE_SAMPLE'
                    use App\Bar as AppBar;
                    CODE_SAMPLE,
                <<<'CODE_SAMPLE'
                    use App\Bar;
                    CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    #[Override]
    public function getNodeTypes(): array
    {
        return [FileNode::class, Namespace_::class];
    }

    #[Override]
    public function refactor(Node $node): FileNode|Node|Namespace_|null
    {
        if ($node instanceof FileNode) {
            if ($node->isNamespaced()) {
                // handle in Namespace_ node
                return null;
            }

            $stmts = $node->stmts;
        } elseif ($node instanceof Namespace_) {
            $stmts = $node->stmts;
        } else {
            return null;
        }
        $hasChanged = false;

        foreach ($stmts as $stmt) {
            if (! $stmt instanceof Use_) {
                continue;
            }

            if (count($stmt->uses) !== 1) {
                continue;
            }

            if (! isset($stmt->uses[0])) {
                continue;
            }
            $aliasName = $stmt->uses[0]->alias instanceof Identifier ? $stmt->uses[0]->alias->toString() : null;

            if ($aliasName === null) {
                continue;
            }

            $useNameToCheck   = $stmt->uses[0]->name->toString();
            $aliasUseLastName = Strings::after($useNameToCheck, '\\', -1) ?? $useNameToCheck;

            foreach ($stmts as $compareStmt) {
                $isClassLike = $compareStmt instanceof Class_
                    || $compareStmt instanceof Interface_
                    || $compareStmt instanceof Trait_
                    || $compareStmt instanceof Enum_;

                if (
                    $isClassLike
                    && $compareStmt->name?->name !== null
                    // Ensure the alias's class name does not match the class/interface/trait/enum class name
                    && strtolower($compareStmt->name->name ?? '') === strtolower($aliasUseLastName)
                ) {
                    // If it did this alias is required and we should move onto the next alias
                    continue 2;
                }

                if ($compareStmt === $stmt) {
                    continue;
                }

                if (! $compareStmt instanceof Use_) {
                    continue;
                }

                if (count($compareStmt->uses) !== 1) {
                    continue;
                }

                if (! isset($compareStmt->uses[0])) {
                    continue;
                }

                $use          = $compareStmt->uses[0]->name->toString();
                $lastName     = Strings::after($use, '\\', -1) ?? $use;
                $useAliasName = $compareStmt->uses[0]->alias instanceof Identifier ? $compareStmt->uses[0]->alias->toString() : null;
                $useHasAlias  = $useAliasName !== null;

                if (
                    // Ensure the alias is not the same as the class name of another use statement
                    strtolower($lastName) === strtolower($aliasName)
                    // Ensure the alias's class name is not the same as the class name of another use statement that has no alias
                    || (! $useHasAlias && strtolower($lastName) === strtolower($aliasUseLastName))
                    // Ensure the alias is not the same as the alias of another use statement that has an alias
                    || ($useHasAlias && strtolower($useAliasName) === strtolower($aliasName))
                ) {
                    // If it matched then this alias is required and we should move onto the next alias
                    continue 2;
                }
            }

            $stmt->uses[0]->alias = null;
            $hasChanged           = true;

            $nodeFinder = new NodeFinder();
            // Get all nodes
            $allNodes = $nodeFinder->findInstanceOf($node, Node::class);

            foreach ($allNodes as $allNode) {
                $this->modifyComments($allNode, $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'name', $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'class', $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'extends', $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'type', $aliasName, $aliasUseLastName);
                $this->modifyNodeClassName($allNode, 'returnType', $aliasName, $aliasUseLastName);
                $this->modifyNodes($allNode, 'implements', $aliasName, $aliasUseLastName);
                $this->modifyNodes($allNode, 'extends', $aliasName, $aliasUseLastName);
                $this->modifyNodes($allNode, 'traits', $aliasName, $aliasUseLastName);
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function modifyComments(Node $node, string $alias, string $className): void
    {
        // Docblocks are stored in the 'comments' attribute
        $comments = $node->getAttribute('comments');

        if (! is_array($comments) || $comments === []) {
            return;
        }

        $newComments = [];

        foreach ($comments as $comment) {
            if (! $comment instanceof Comment) {
                continue;
            }

            if ($comment instanceof Doc && preg_match("/(\W)$alias(\W)/", $comment->getText()) === 1) {
                $newText = preg_replace("/(\W)$alias(\W)/", "$1$className$2", $comment->getText());

                $newComments[] = new Doc(
                    text: $newText ?? $comment->getText(),
                );

                continue;
            }

            $newComments[] = $comment;
        }

        // Set the filtered comments back to the node
        $node->setAttribute('comments', $newComments);
    }

    private function modifyNodeClassName(Node $node, string $property, string $alias, string $className): void
    {
        $nameNode = $node->$property ?? null;

        if (! $nameNode instanceof FullyQualified) {
            return;
        }

        $originalName = $this->getOriginalName($nameNode);

        if ($originalName !== null && $originalName->name === $alias) {
            $newNameNode = new FullyQualified($nameNode->name);
            $newNameNode->setAttribute('originalName', $className);

            $node->$property = $newNameNode;
        }
    }

    private function modifyNodes(Node $node, string $property, string $alias, string $className): void
    {
        $nameNodes = $node->$property ?? null;

        if (! is_array($nameNodes)) {
            return;
        }

        $newNameNodes = [];

        $existingNameNodes = array_filter($nameNodes, static fn (mixed $nameNode): bool => $nameNode instanceof Node);

        foreach ($existingNameNodes as $nameNode) {
            if ($nameNode instanceof FullyQualified) {
                $originalName = $this->getOriginalName($nameNode);

                if ($originalName !== null && $originalName->name === $alias) {
                    $newClass = new FullyQualified($nameNode->name);
                    $newClass->setAttribute('originalName', $className);

                    $newNameNodes[] = $newClass;

                    continue;
                }
            }

            $newNameNodes[] = $nameNode;
        }

        $node->$property = $newNameNodes;
    }

    /**
     * Resolve the node's "originalName" attribute when it holds a Name node.
     */
    private function getOriginalName(Node $node): Name|null
    {
        /** @var Name|null $originalName */
        $originalName = $node->getAttribute('originalName');

        return $originalName instanceof Name ? $originalName : null;
    }
}
