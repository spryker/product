<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Zed\Product\Business\Exception\MissingProductException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group AbstractManagementTest
 * Add your own group annotations below this line
 */
class AbstractManagementTest extends FacadeTestAbstract
{
    public function testCreateProductAbstractShouldCreateProductAbstract(): void
    {
        $idProductAbstract = $this->productFacade->createProductAbstract($this->productAbstractTransfer);

        $this->assertTrue($idProductAbstract > 0);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->assertCreateProductAbstract($this->productAbstractTransfer);
    }

    public function testSaveProductAbstractShouldUpdateProductAbstract(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName(
                static::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()],
            );
        }

        $idProductAbstract = $this->productFacade->saveProductAbstract($this->productAbstractTransfer);

        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->assertSaveProductAbstract($this->productAbstractTransfer);
    }

    public function testHasProductAbstractShouldReturnFalse(): void
    {
        $this->assertFalse(
            $this->productFacade->hasProductAbstract('sku that does not exist'),
        );
    }

    public function testHasProductAbstractShouldReturnTrue(): void
    {
        $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->assertTrue(
            $this->productFacade->hasProductAbstract(static::ABSTRACT_SKU),
        );
    }

    public function testGetProductAbstractIdBySku(): void
    {
        $expectedId = $this->createNewProductAbstractAndAssertNoTouchExists();
        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku(static::ABSTRACT_SKU);

        $this->assertEquals(
            $expectedId,
            $idProductAbstract,
        );
    }

    public function testGetProductAbstractIdBySkuShouldReturnNull(): void
    {
        $idProductAbstract = $this->productFacade->findProductAbstractIdBySku('INVALIDSKU');

        $this->assertNull($idProductAbstract);
    }

    public function testGetProductAbstractById(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();
        $productAbstract = $this->productFacade->findProductAbstractById($idProductAbstract);

        $this->assertInstanceOf(ProductAbstractTransfer::class, $productAbstract);
        $this->assertSame(static::ABSTRACT_SKU, $productAbstract->getSku());
    }

    public function testGetProductAbstractCollectionByIdsIndexedByIdsReturnsAProductCollectionWithMergedAttributesWhenProductsByIdAreFound(): void
    {
        // Arrange
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        // Act
        $productAbstractCollection = $this->productAbstractManager->findProductAbstractByIdsIndexedByProductAbstractIds([$idProductAbstract]);

        // Assert
        $this->assertIsArray($productAbstractCollection);
        $this->assertNotEmpty($productAbstractCollection);
        $this->assertNotEmpty($productAbstractCollection[$idProductAbstract]);
        $this->assertInstanceOf(ProductAbstractTransfer::class, $productAbstractCollection[$idProductAbstract]);
        $this->assertSame(static::ABSTRACT_SKU, $productAbstractCollection[$idProductAbstract]->getSku());
    }

    public function testGetProductAbstractByIdShouldReturnNull(): void
    {
        $productAbstract = $this->productFacade->findProductAbstractById(1010001);

        $this->assertNull($productAbstract);
    }

    public function testGetProductAbstractCollectionByIdsIndexedByIdsReturnsAnEmptyProductCollectionWhenProductsByIdsAreNotFound(): void
    {
        // Act
        $productAbstractCollection = $this->productAbstractManager->findProductAbstractByIdsIndexedByProductAbstractIds([1010001]);

        // Assert
        $this->assertIsArray($productAbstractCollection);
        $this->assertEmpty($productAbstractCollection);
    }

    public function testGetAbstractSkuFromProductConcrete(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $this->productConcreteManager->createProductConcrete($this->productConcreteTransfer);

        $abstractSku = $this->productFacade->getAbstractSkuFromProductConcrete(static::CONCRETE_SKU);

        $this->assertSame(static::ABSTRACT_SKU, $abstractSku);
    }

    public function testGetAbstractSkuFromProductConcreteShouldThrowException(): void
    {
        $this->expectException(MissingProductException::class);
        $this->expectExceptionMessage('Tried to retrieve a product concrete with sku INVALIDSKU, but it does not exist.');

        $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productFacade->getAbstractSkuFromProductConcrete('INVALIDSKU');
    }

    public function testGetLocalizedProductAbstractName(): void
    {
        $nameEN = $this->productFacade->getLocalizedProductAbstractName(
            $this->productAbstractTransfer,
            $this->locales['en_US'],
        );

        $nameDE = $this->productFacade->getLocalizedProductAbstractName(
            $this->productAbstractTransfer,
            $this->locales['de_DE'],
        );

        $this->assertSame(static::PRODUCT_ABSTRACT_NAME['en_US'], $nameEN);
        $this->assertSame(static::PRODUCT_ABSTRACT_NAME['de_DE'], $nameDE);
    }

    public function testTouchProductAbstractShouldAlsoTouchItsVariants(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $idProductConcrete = $this->productConcreteManager->createProductConcrete($this->productConcreteTransfer);

        $this->productFacade->touchProductAbstract($idProductAbstract);

        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE, $idProductConcrete);
    }

    public function testTouchProductActiveShouldTouchActiveLogic(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productFacade->touchProductActive($idProductAbstract);

        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
    }

    public function testTouchProductInactiveShouldTouchInactiveLogic(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productFacade->touchProductActive($idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        $this->productFacade->touchProductInactive($idProductAbstract);
        $this->tester->assertTouchInactive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchInactive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
    }

    public function testTouchProductDeletedShouldTouchDeletedLogic(): void
    {
        $idProductAbstract = $this->createNewProductAbstractAndAssertNoTouchExists();

        $this->productFacade->touchProductDeleted($idProductAbstract);

        $this->tester->assertTouchDeleted(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertTouchDeleted(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);
    }

    protected function createNewProductAbstractAndAssertNoTouchExists(): int
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $this->tester->assertNoTouchEntry(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $idProductAbstract);
        $this->tester->assertNoTouchEntry(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $idProductAbstract);

        return $idProductAbstract;
    }

    protected function assertCreateProductAbstract(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $createdProductEntity = $this->getProductAbstractEntityById($productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($createdProductEntity);
        $this->assertSame($productAbstractTransfer->getSku(), $createdProductEntity->getSku());
    }

    protected function assertSaveProductAbstract(ProductAbstractTransfer $productAbstractTransfer): void
    {
        $updatedProductEntity = $this->getProductAbstractEntityById($productAbstractTransfer->getIdProductAbstract());

        $this->assertNotNull($updatedProductEntity);
        $this->assertSame($this->productAbstractTransfer->getSku(), $updatedProductEntity->getSku());

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $expectedProductName = static::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()];

            $this->assertSame($expectedProductName, $localizedAttribute->getName());
        }
    }

    protected function getProductAbstractEntityById(int $idProductAbstract): ?SpyProductAbstract
    {
        return $this->productQueryContainer
            ->queryProductAbstract()
            ->filterByIdProductAbstract($idProductAbstract)
            ->findOne();
    }
}
