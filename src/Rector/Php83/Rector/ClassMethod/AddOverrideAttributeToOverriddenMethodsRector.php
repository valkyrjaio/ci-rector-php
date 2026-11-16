<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Rector package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

/*
 * Forked from the rule of the same name in rector/rector 2.7.0. Later versions deprecate that rule
 * and make it throw.
 *
 * Copyright (c) 2017-present Tomáš Votruba (https://tomasvotruba.cz)
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Valkyrja\Rector\Php83\Rector\ClassMethod;

use Override;
use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpParser\AstResolver;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\ConfiguredCodeSample;
use Rector\RuleDoc\Exception\PoorDocumentationException;
use Rector\RuleDoc\RuleDefinition;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Stringable;

use function array_merge;
use function count;

use const false;
use const true;

final class AddOverrideAttributeToOverriddenMethodsRector extends AbstractRector implements MinPhpVersionInterface, ConfigurableRectorInterface
{
    /**
     * @api
     */
    public const string ALLOW_OVERRIDE_EMPTY_METHOD = 'allow_override_empty_method';

    /**
     * @api
     */
    public const string ADD_TO_INTERFACE_METHODS = 'add_to_interface_methods';

    private bool $allowOverrideEmptyMethod = false;

    private bool $addToInterfaceMethods = false;

    private bool $hasChanged = false;

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private readonly AstResolver $astResolver,
        private readonly ValueResolver $valueResolver,
    ) {
    }

    /**
     * @throws PoorDocumentationException
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add override attribute to overridden methods', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
                    class ParentClass
                    {
                        public function foo()
                        {
                            echo 'default';
                        }
                    }

                    final class ChildClass extends ParentClass
                    {
                        public function foo()
                        {
                            echo 'override default';
                        }
                    }
                    CODE_SAMPLE,
                <<<'CODE_SAMPLE'
                    class ParentClass
                    {
                        public function foo()
                        {
                            echo 'default';
                        }
                    }

                    final class ChildClass extends ParentClass
                    {
                        #[\Override]
                        public function foo()
                        {
                            echo 'override default';
                        }
                    }
                    CODE_SAMPLE,
                [self::ALLOW_OVERRIDE_EMPTY_METHOD => false]
            ),
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
                    interface ParentInterface
                    {
                        public function foo();
                    }

                    final class ChildClass implements ParentInterface
                    {
                        public function foo()
                        {
                            echo 'implements interface';
                        }
                    }
                    CODE_SAMPLE,
                <<<'CODE_SAMPLE'
                    interface ParentInterface
                    {
                        public function foo();
                    }

                    final class ChildClass implements ParentInterface
                    {
                        #[\Override]
                        public function foo()
                        {
                            echo 'implements interface';
                        }
                    }
                    CODE_SAMPLE,
                [self::ADD_TO_INTERFACE_METHODS => true]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    #[Override]
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param array<array-key, mixed> $configuration
     */
    #[Override]
    public function configure(array $configuration): void
    {
        $this->allowOverrideEmptyMethod = ($configuration[self::ALLOW_OVERRIDE_EMPTY_METHOD] ?? false) === true;
        $this->addToInterfaceMethods    = ($configuration[self::ADD_TO_INTERFACE_METHODS] ?? false) === true;
    }

    #[Override]
    public function refactor(Node $node): Class_|null
    {
        $this->hasChanged = false;

        if (! $node instanceof Class_ || $this->classAnalyzer->isAnonymousClass($node) || $this->shouldSkipNode($node)) {
            return null;
        }

        $className = (string) $this->getName($node);

        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection        = $this->reflectionProvider->getClass($className);
        $parentClassReflections = $classReflection->getParents();

        // Stringable is an interface, so the empty-method option needs the interfaces too.
        if ($this->addToInterfaceMethods || $this->allowOverrideEmptyMethod) {
            $parentClassReflections = array_merge($parentClassReflections, $classReflection->getInterfaces());
        }

        // Traits go last, so a parent class that declares the method wins over a trait.
        if ($this->allowOverrideEmptyMethod) {
            $parentClassReflections = array_merge($parentClassReflections, $classReflection->getTraits());
        }

        foreach ($node->getMethods() as $classMethod) {
            $this->processAddOverrideAttribute($classMethod, $parentClassReflections);
        }

        if (! $this->hasChanged) {
            return null;
        }

        return $node;
    }

    #[Override]
    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_83;
    }

    /**
     * Add the attribute when an ancestor holds a method that the child method overrides.
     *
     * @param ClassReflection[] $parentClassReflections The parents, then the interfaces, then the traits
     */
    private function processAddOverrideAttribute(ClassMethod $classMethod, array $parentClassReflections): void
    {
        if ($this->shouldSkipClassMethod($classMethod)) {
            return;
        }

        $classMethodName   = $classMethod->name->toString();
        $shouldAddOverride = false;

        foreach ($parentClassReflections as $parentClassReflection) {
            if (! $parentClassReflection->hasNativeMethod($classMethodName)) {
                continue;
            }

            $parentMethod = $parentClassReflection->getNativeMethod($classMethodName);

            // A private parent method is not overridden, so no ancestor further up counts either.
            if ($parentMethod->isPrivate()) {
                break;
            }

            if ($this->shouldSkipParentClassMethod($parentClassReflection, $classMethod)) {
                continue;
            }

            if ($parentClassReflection->isTrait() && ! $parentMethod->isAbstract()) {
                break;
            }

            $shouldAddOverride = true;

            break;
        }

        if ($shouldAddOverride) {
            $classMethod->attrGroups[] = new AttributeGroup([new Attribute(new FullyQualified(Override::class))]);
            $this->hasChanged          = true;
        }
    }

    /**
     * Determine whether a method can never take the attribute.
     */
    private function shouldSkipClassMethod(ClassMethod $classMethod): bool
    {
        if ($this->isName($classMethod->name, MethodName::CONSTRUCT)) {
            return true;
        }

        if ($classMethod->isPrivate()) {
            return true;
        }

        if ($this->phpAttributeAnalyzer->hasPhpAttribute($classMethod, Override::class)) {
            return true;
        }

        // A test setUp or tearDown that calls the parent is an override by convention already.
        return $this->isNames($classMethod, ['setUp', 'tearDown']) && $this->hasParentCall($classMethod);
    }

    /**
     * Determine whether the method calls the parent method of the same name as a statement.
     */
    private function hasParentCall(ClassMethod $classMethod): bool
    {
        foreach ((array) $classMethod->stmts as $stmt) {
            if (! $stmt instanceof Expression || ! $stmt->expr instanceof StaticCall) {
                continue;
            }

            if ($this->isName($stmt->expr->class, 'parent') && $this->isName($stmt->expr->name, $classMethod->name->toString())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the ancestor's method does not count as an overridden method.
     */
    private function shouldSkipParentClassMethod(ClassReflection $parentClassReflection, ClassMethod $classMethod): bool
    {
        if ($this->allowOverrideEmptyMethod && $parentClassReflection->getName() === Stringable::class) {
            return false;
        }

        if ($parentClassReflection->isInterface()) {
            return ! $this->addToInterfaceMethods;
        }

        $parentClass = $this->astResolver->resolveClassFromClassReflection($parentClassReflection);

        if (! $parentClass instanceof ClassLike) {
            return true;
        }

        $methodName        = $classMethod->name->toString();
        $parentClassMethod = $parentClass->getMethod($methodName) ?? $this->resolveClassMethodFromTraitUse($parentClass, $methodName);

        if (! $parentClassMethod instanceof ClassMethod) {
            return true;
        }

        if ($this->allowOverrideEmptyMethod) {
            return false;
        }

        // Upstream treats the attribute on an abstract or empty parent method as clutter.
        if ($parentClassMethod->isAbstract() || $parentClassMethod->stmts === null || $parentClassMethod->stmts === []) {
            return true;
        }

        if (count($parentClassMethod->stmts) !== 1) {
            return false;
        }

        $soleStmt = $parentClassMethod->stmts[0];

        // A sole `return null;` or `throw` marks a default that a child is expected to replace.
        if ($soleStmt instanceof Return_ && $soleStmt->expr instanceof Expr && $this->valueResolver->isNull($soleStmt->expr)) {
            return true;
        }

        return $soleStmt instanceof Expression && $soleStmt->expr instanceof Throw_;
    }

    /**
     * Find the method in a trait that the class uses.
     */
    private function resolveClassMethodFromTraitUse(ClassLike $classLike, string $methodName): ClassMethod|null
    {
        foreach ($classLike->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $traitName) {
                $traitClass = $this->astResolver->resolveClassFromName($traitName->toString());

                if (! $traitClass instanceof Trait_) {
                    continue;
                }

                $traitClassMethod = $traitClass->getMethod($methodName);

                if ($traitClassMethod instanceof ClassMethod) {
                    return $traitClassMethod;
                }
            }
        }

        return null;
    }

    /**
     * Determine whether the class has no ancestor that could declare one of its methods.
     */
    private function shouldSkipNode(Class_ $class): bool
    {
        if ($class->extends instanceof Name || $class->getTraitUses() !== []) {
            return false;
        }

        if ($this->addToInterfaceMethods && $class->implements !== []) {
            return false;
        }

        if ($this->allowOverrideEmptyMethod) {
            foreach ($class->implements as $implement) {
                if ($this->isName($implement, Stringable::class)) {
                    return false;
                }
            }
        }

        return true;
    }
}
