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
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerTest\Zed\Product\ProductBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Facade
 * @group UpdateProductCollectionTest
 *
 * Add your own group annotations below this line
 */
class UpdateProductCollectionTest extends Unit
{
    protected ProductBusinessTester $tester;

    public function testUpdateProductCollectionPersistsChangedFields(): void
    {
        // Arrange
        $seededProductConcreteTransfer = $this->tester->haveProduct();
        $newSku = sprintf('updated-concrete-%s', uniqid());
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setIdProductConcrete($seededProductConcreteTransfer->getIdProductConcrete())
                    ->setFkProductAbstract($seededProductConcreteTransfer->getFkProductAbstract())
                    ->setAbstractSku($seededProductConcreteTransfer->getAbstractSku())
                    ->setSku($newSku),
            );

        // Act
        $productConcreteCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->updateProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $productConcreteCollectionResponseTransfer->getErrors());
        $productEntity = SpyProductQuery::create()
            ->findOneByIdProduct($seededProductConcreteTransfer->getIdProductConcrete());
        $this->assertNotNull($productEntity);
        $this->assertSame($newSku, $productEntity->getSku());
    }

    public function testUpdateProductCollectionReturnsErrorWhenIdProductConcreteIsMissing(): void
    {
        // Arrange
        $seededProductConcreteTransfer = $this->tester->haveProduct();
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setSku($seededProductConcreteTransfer->getSku())
                    ->setAbstractSku($seededProductConcreteTransfer->getAbstractSku()),
            );

        // Act
        $productConcreteCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->updateProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
        $this->assertStringContainsString(
            'Field "idProductConcrete" is required',
            (string)$productConcreteCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testUpdateProductCollectionReturnsErrorWhenRenamingToTakenSku(): void
    {
        // Arrange
        $seededProductConcreteTransfer = $this->tester->haveProduct();
        $otherProductConcreteTransfer = $this->tester->haveProduct();
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setIdProductConcrete($seededProductConcreteTransfer->getIdProductConcrete())
                    ->setFkProductAbstract($seededProductConcreteTransfer->getFkProductAbstract())
                    ->setAbstractSku($seededProductConcreteTransfer->getAbstractSku())
                    ->setSku($otherProductConcreteTransfer->getSku()),
            );

        // Act
        $productConcreteCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->updateProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
        $productEntity = SpyProductQuery::create()
            ->findOneByIdProduct($seededProductConcreteTransfer->getIdProductConcrete());
        $this->assertSame($seededProductConcreteTransfer->getSku(), $productEntity->getSku());
    }

    public function testUpdateProductCollectionReturnsErrorForUnknownProductAndPersistsNothing(): void
    {
        // Arrange
        $seededProductConcreteTransfer = $this->tester->haveProduct();
        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct(
                (new ProductConcreteTransfer())
                    ->setIdProductConcrete($this->getUnknownIdProductConcrete())
                    ->setSku(sprintf('missing-concrete-%s', uniqid())),
            );

        // Act
        $productConcreteCollectionResponseTransfer = $this->tester->getLocator()->product()->facade()
            ->updateProductCollection($productConcreteCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
        $this->assertNotNull(SpyProductQuery::create()->findOneBySku($seededProductConcreteTransfer->getSku()));
    }

    protected function getUnknownIdProductConcrete(): int
    {
        $productEntity = SpyProductQuery::create()->orderByIdProduct(Criteria::DESC)->findOne();

        return ($productEntity?->getIdProduct() ?? 0) + 1;
    }
}
