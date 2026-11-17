<?php

declare(strict_types=1);

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithSetUpFixture;

final class SkipTearDownOverrideTest extends ParentWithSetUpFixture
{
    protected function tearDown(): void
    {
        parent::tearDown();
        $result = null;
    }
}
