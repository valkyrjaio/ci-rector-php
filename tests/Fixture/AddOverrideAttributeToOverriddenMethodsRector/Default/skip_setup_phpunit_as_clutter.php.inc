<?php

namespace App\Fixture\Default;

use Valkyrja\Rector\Tests\Fixtures\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Abstract\ParentWithSetUpFixture;

final class SkipSetupPHPUnitAsClutter extends ParentWithSetUpFixture
{
    protected function setUp(): void
    {
        parent::setUp();

        $someValue = 1;
    }
}
