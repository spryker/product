<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use SprykerTest\Zed\Product\ProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Facade
 * @group CreateProductCollectionTest
 *
 * Add your own group annotations below this line
 */
class CreateProductCollectionTest extends Unit
{
    protected const string UNKNOWN_ABSTRACT_SKU = 'non-existent-abstract-sku';

    protected ProductBusinessTester $tester;

    public function testCreateProductCollectionReturnsErrorWhenConcreteSkuAlreadyExists(): void
    {
        // Arrange
        $existingProductConcreteTransfer = $this->tester->haveProduct();
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku($existingProductConcreteTransfer->getSku())
                    ->setAbstractSku($existingProductConcreteTransfer->getAbstractSku()),
            );

        // Act
        $productConcreteCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->createProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
        $this->assertSame(
            $existingProductConcreteTransfer->getSku(),
            $productConcreteCollectionResponseTransfer->getErrors()->offsetGet(0)->getEntityIdentifier(),
        );
    }

    public function testCreateProductCollectionReturnsErrorWhenAbstractSkuIsUnknown(): void
    {
        // Arrange
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku(sprintf('fresh-concrete-%s', uniqid()))
                    ->setAbstractSku(static::UNKNOWN_ABSTRACT_SKU),
            );

        // Act
        $productConcreteCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->createProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
    }

    public function testCreateProductCollectionReturnsErrorWhenNoAbstractProductIsReferenced(): void
    {
        // Arrange
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct((new ProductConcreteTransfer())->setSku(sprintf('fresh-concrete-%s', uniqid())));

        // Act
        $productConcreteCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->createProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
        $this->assertStringContainsString(
            'is required when no abstract product is referenced',
            (string)$productConcreteCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testCreateProductCollectionReturnsErrorWhenValidFromIsLaterThanValidTo(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku(sprintf('fresh-concrete-%s', uniqid()))
                    ->setAbstractSku($productAbstractTransfer->getSku())
                    ->setValidFrom('2026-02-01 00:00:00')
                    ->setValidTo('2026-01-01 00:00:00'),
            );

        // Act
        $productConcreteCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->createProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
        $this->assertStringContainsString(
            'must not be later than',
            (string)$productConcreteCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testCreateProductCollectionPersistsProductWhenValidityRangeIsNotInverted(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $sku = sprintf('fresh-concrete-%s', uniqid());
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku($sku)
                    ->setAbstractSku($productAbstractTransfer->getSku())
                    ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
                    ->setValidFrom('2026-01-01 00:00:00')
                    ->setValidTo('2026-02-01 00:00:00'),
            );

        // Act
        $productConcreteCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->createProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $productConcreteCollectionResponseTransfer->getErrors());
        $this->assertNotNull(SpyProductQuery::create()->findOneBySku($sku));
    }

    public function testCreateProductCollectionPersistsNothingWhenValidationFails(): void
    {
        // Arrange
        $sku = sprintf('fresh-concrete-%s', uniqid());
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku($sku)
                    ->setAbstractSku(static::UNKNOWN_ABSTRACT_SKU),
            );

        // Act
        $this->tester->getLocator()->product()->facade()->createProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertNull(SpyProductQuery::create()->findOneBySku($sku));
    }
}
