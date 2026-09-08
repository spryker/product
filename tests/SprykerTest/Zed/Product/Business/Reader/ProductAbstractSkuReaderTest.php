<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Reader;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Reader\ProductAbstractSkuReader;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Reader
 * @group ProductAbstractSkuReaderTest
 *
 * Add your own group annotations below this line
 */
class ProductAbstractSkuReaderTest extends Unit
{
    protected const string PERSISTED_SKU = 'persisted-abstract-sku';

    protected const string OTHER_PERSISTED_SKU = 'other-persisted-abstract-sku';

    protected const string UNKNOWN_SKU = 'unknown-abstract-sku';

    public function testGetPersistedSkusReturnsEmptyArrayForEmptyInputWithoutQuerying(): void
    {
        // Arrange
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $productRepositoryMock->expects($this->never())->method('getProductAbstractCollection');

        // Act
        $existingSkus = (new ProductAbstractSkuReader($productRepositoryMock))->getExistingSkus([]);

        // Assert
        $this->assertSame([], $existingSkus);
    }

    public function testGetPersistedSkusReturnsFoundSkusKeyedBySku(): void
    {
        // Arrange
        $productRepositoryMock = $this->createProductRepositoryMock([static::PERSISTED_SKU]);

        // Act
        $existingSkus = (new ProductAbstractSkuReader($productRepositoryMock))
            ->getExistingSkus([static::PERSISTED_SKU, static::UNKNOWN_SKU]);

        // Assert
        $this->assertSame([static::PERSISTED_SKU => true], $existingSkus);
    }

    public function testGetPersistedSkusReturnsEmptyArrayWhenNothingIsPersisted(): void
    {
        // Arrange
        $productRepositoryMock = $this->createProductRepositoryMock([]);

        // Act
        $existingSkus = (new ProductAbstractSkuReader($productRepositoryMock))
            ->getExistingSkus([static::UNKNOWN_SKU]);

        // Assert
        $this->assertSame([], $existingSkus);
    }

    public function testGetPersistedSkusResolvesWithASingleQuery(): void
    {
        // Arrange
        $productRepositoryMock = $this->createProductRepositoryMock([static::PERSISTED_SKU, static::OTHER_PERSISTED_SKU]);
        $productRepositoryMock->expects($this->once())->method('getProductAbstractCollection');

        // Act
        (new ProductAbstractSkuReader($productRepositoryMock))
            ->getExistingSkus([static::PERSISTED_SKU, static::OTHER_PERSISTED_SKU]);
    }

    public function testGetPersistedSkusPassesEverySkuAsCondition(): void
    {
        // Arrange
        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $productRepositoryMock
            ->method('getProductAbstractCollection')
            ->with($this->callback(function (ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer): bool {
                return $productAbstractCriteriaTransfer->getProductAbstractConditions()?->getSkus()
                    === [static::PERSISTED_SKU, static::UNKNOWN_SKU];
            }))
            ->willReturn(new ProductAbstractCollectionTransfer());

        // Act
        (new ProductAbstractSkuReader($productRepositoryMock))
            ->getExistingSkus([static::PERSISTED_SKU, static::UNKNOWN_SKU]);
    }

    public function testGetPersistedSkusIgnoresPersistedProductAbstractsWithoutSku(): void
    {
        // Arrange
        $productAbstractCollectionTransfer = (new ProductAbstractCollectionTransfer())
            ->addProductAbstract(new ProductAbstractTransfer())
            ->addProductAbstract((new ProductAbstractTransfer())->setSku(static::PERSISTED_SKU));

        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $productRepositoryMock->method('getProductAbstractCollection')->willReturn($productAbstractCollectionTransfer);

        // Act
        $existingSkus = (new ProductAbstractSkuReader($productRepositoryMock))
            ->getExistingSkus([static::PERSISTED_SKU]);

        // Assert
        $this->assertSame([static::PERSISTED_SKU => true], $existingSkus);
    }

    /**
     * @param array<string> $existingSkus
     *
     * @return \Spryker\Zed\Product\Persistence\ProductRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductRepositoryMock(array $existingSkus): ProductRepositoryInterface
    {
        $productAbstractCollectionTransfer = new ProductAbstractCollectionTransfer();

        foreach ($existingSkus as $persistedSku) {
            $productAbstractCollectionTransfer->addProductAbstract(
                (new ProductAbstractTransfer())->setSku($persistedSku),
            );
        }

        $productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $productRepositoryMock->method('getProductAbstractCollection')->willReturn($productAbstractCollectionTransfer);

        return $productRepositoryMock;
    }
}
