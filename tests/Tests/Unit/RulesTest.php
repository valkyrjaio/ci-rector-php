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

use Rector\Configuration\RectorConfigBuilder;
use Valkyrja\Rector\Rules;
use Valkyrja\Rector\Tests\Abstract\RectorTestCase;

final class RulesTest extends RectorTestCase
{
    public function testGetConfigReturnsConfigBuilder(): void
    {
        self::assertInstanceOf(RectorConfigBuilder::class, Rules::getConfig());
    }
}
