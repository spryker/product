<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use SprykerTest\Zed\Product\ProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Facade
 * @group CreateProductAbstractCollectionTest
 *
 * Add your own group annotations below this line
 */
class CreateProductAbstractCollectionTest extends Unit
{
    protected ProductBusinessTester $tester;

    public function testCreateProductAbstractCollectionReturnsErrorWhenAbstractSkuAlreadyExists(): void
    {
        // Arrange
        $existingProductAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractCollectionRequestTransfer = (new ProductAbstractCollectionRequestTransfer())
            ->addProductAbstract((new ProductAbstractTransfer())->setSku($existingProductAbstractTransfer->getSku()));

        // Act
        $productAbstractCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->createProductAbstractCollection($productAbstractCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productAbstractCollectionResponseTransfer->getErrors());
        $this->assertSame(
            $existingProductAbstractTransfer->getSku(),
            $productAbstractCollectionResponseTransfer->getErrors()->offsetGet(0)->getEntityIdentifier(),
        );
    }

    public function testCreateProductAbstractCollectionReturnsErrorWhenNewFromIsLaterThanNewTo(): void
    {
        // Arrange
        $productAbstractCollectionRequestTransfer = (new ProductAbstractCollectionRequestTransfer())
            ->addProductAbstract(
                (new ProductAbstractTransfer())
                    ->setSku(sprintf('fresh-abstract-%s', uniqid()))
                    ->setNewFrom('2026-02-01 00:00:00')
                    ->setNewTo('2026-01-01 00:00:00'),
            );

        // Act
        $productAbstractCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->createProductAbstractCollection($productAbstractCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productAbstractCollectionResponseTransfer->getErrors());
        $this->assertStringContainsString(
            'must not be later than',
            (string)$productAbstractCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testCreateProductAbstractCollectionPassesWhenNewRangeIsNotInverted(): void
    {
        // Arrange
        $productAbstractCollectionRequestTransfer = (new ProductAbstractCollectionRequestTransfer())
            ->addProductAbstract(
                (new ProductAbstractTransfer())
                    ->setSku(sprintf('fresh-abstract-%s', uniqid()))
                    ->setNewFrom('2026-01-01 00:00:00')
                    ->setNewTo('2026-02-01 00:00:00'),
            );

        // Act
        $productAbstractCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->createProductAbstractCollection($productAbstractCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $productAbstractCollectionResponseTransfer->getErrors());
    }
}
