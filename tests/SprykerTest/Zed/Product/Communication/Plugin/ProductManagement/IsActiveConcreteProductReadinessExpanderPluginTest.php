<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Communication\Plugin\ProductManagement;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Communication\Plugin\ProductManagement\IsActiveConcreteProductReadinessProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group IsActiveConcreteProductReadinessExpanderPluginTest
 * Add your own group annotations below this line
 */
class IsActiveConcreteProductReadinessExpanderPluginTest extends Unit
{
    public function testProvideReturnsYesForActiveProduct(): void
    {
        // Arrange
        $productConcreteReadinessRequestTransfer = (new ProductConcreteReadinessRequestTransfer())
            ->setProductConcrete((new ProductConcreteTransfer())->setIsActive(true));

        // Act
        $result = (new IsActiveConcreteProductReadinessProviderPlugin())->provide(
            $productConcreteReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result);
        $productReadiness = $result[0];
        $this->assertSame('Status is active', $productReadiness->getTitle());
        $this->assertSame('Yes', $productReadiness->getValues()[0]);
    }

    public function testProvideReturnsNoForInactiveProduct(): void
    {
        // Arrange
        $productConcreteReadinessRequestTransfer = (new ProductConcreteReadinessRequestTransfer())
            ->setProductConcrete((new ProductConcreteTransfer())->setIsActive(false));

        // Act
        $result = (new IsActiveConcreteProductReadinessProviderPlugin())->provide(
            $productConcreteReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result);
        $this->assertSame('No', $result[0]->getValues()[0]);
    }
}
