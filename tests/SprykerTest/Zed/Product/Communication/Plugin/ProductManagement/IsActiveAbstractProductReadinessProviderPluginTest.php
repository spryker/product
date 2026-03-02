<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Communication\Plugin\ProductManagement;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Communication\Plugin\ProductManagement\IsActiveAbstractProductReadinessProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group IsActiveAbstractProductReadinessProviderPluginTest
 * Add your own group annotations below this line
 */
class IsActiveAbstractProductReadinessProviderPluginTest extends Unit
{
    public function testProvideReturnsYesWhenAtLeastOneConcreteIsActive(): void
    {
        // Arrange
        $productConcretes = new ArrayObject([
            (new ProductConcreteTransfer())->setIsActive(false),
            (new ProductConcreteTransfer())->setIsActive(true),
        ]);

        $productAbstractReadinessRequestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductConcretes($productConcretes);

        // Act
        $result = (new IsActiveAbstractProductReadinessProviderPlugin())->provide(
            $productAbstractReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result->getProductReadiness());
        $productReadiness = $result->getProductReadiness()[0];
        $this->assertSame('At least one concrete is active', $productReadiness->getTitle());
        $this->assertSame('Yes', $productReadiness->getValues()[0]);
    }

    public function testProvideReturnsNoWhenNoConcreteIsActive(): void
    {
        // Arrange
        $productConcretes = new ArrayObject([
            (new ProductConcreteTransfer())->setIsActive(false),
            (new ProductConcreteTransfer())->setIsActive(false),
        ]);

        $productAbstractReadinessRequestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductConcretes($productConcretes);

        // Act
        $result = (new IsActiveAbstractProductReadinessProviderPlugin())->provide(
            $productAbstractReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(1, $result->getProductReadiness());
        $this->assertSame('No', $result->getProductReadiness()[0]->getValues()[0]);
    }
}
