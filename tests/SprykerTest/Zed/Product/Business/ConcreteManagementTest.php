<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Zed\Product\Business\Exception\MissingProductException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group ConcreteManagementTest
 * Add your own group annotations below this line
 */
class ConcreteManagementTest extends FacadeTestAbstract
{
    protected function a222setupDefaultProducts(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);

        $idProductConcrete = $this->productConcreteManager->createProductConcrete($this->productConcreteTransfer);
        $this->productConcreteTransfer->setIdProductConcrete($idProductConcrete);
    }

    public function testCreateProductConcreteShouldCreateProductConcrete(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);

        $idProductConcrete = $this->productFacade->createProductConcrete($this->productConcreteTransfer);

        $this->productConcreteTransfer->setIdProductConcrete($idProductConcrete);
        $this->assertTrue($this->productConcreteTransfer->getIdProductConcrete() > 0);
        $this->assertCreateProductConcrete($this->productConcreteTransfer);
    }

    public function testSaveProductAbstractShouldUpdateProductAbstract(): void
    {
        $this->setupDefaultProducts();

        foreach ($this->productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName(
                static::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()],
            );
        }

        $idProductConcrete = $this->productFacade->saveProductConcrete($this->productConcreteTransfer);

        $this->assertEquals($this->productConcreteTransfer->getIdProductConcrete(), $idProductConcrete);
        $this->assertSaveProductConcrete($this->productConcreteTransfer);
    }

    public function testHasProductConcreteShouldReturnTrue(): void
    {
        $this->setupDefaultProducts();

        $exists = $this->productFacade->hasProductConcrete($this->productConcreteTransfer->getSku());
        $this->assertTrue($exists);
    }

    public function testHasProductConcreteShouldReturnFalse(): void
    {
        $exists = $this->productFacade->hasProductConcrete('INVALIDSKU');
        $this->assertFalse($exists);
    }

    public function testTouchProductConcreteShouldAlsoTouchItsAbstract(): void
    {
        $this->createNewProductAndAssertNoTouchExists();

        $this->productFacade->touchProductConcrete($this->productConcreteTransfer->getIdProductConcrete());

        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE, $this->productConcreteTransfer->getIdProductConcrete());
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $this->productConcreteTransfer->getFkProductAbstract());
        $this->tester->assertTouchActive(ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP, $this->productConcreteTransfer->getFkProductAbstract());
    }

    public function testTouchProductActiveShouldTouchActiveLogic(): void
    {
        $this->createNewProductAndAssertNoTouchExists();

        $this->productFacade->touchProductConcreteActive($this->productAbstractTransfer->getIdProductAbstract());

        $this->tester->assertTouchActive(
            ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE,
            $this->productAbstractTransfer->getIdProductAbstract(),
        );
    }

    public function testTouchProductInactiveShouldTouchInactiveLogic(): void
    {
        $this->createNewProductAndAssertNoTouchExists();

        $this->productFacade->touchProductConcreteActive($this->productAbstractTransfer->getIdProductAbstract());
        $this->tester->assertTouchActive(
            ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE,
            $this->productAbstractTransfer->getIdProductAbstract(),
        );

        $this->productFacade->touchProductConcreteInactive($this->productAbstractTransfer->getIdProductAbstract());
        $this->tester->assertTouchInactive(
            ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE,
            $this->productAbstractTransfer->getIdProductAbstract(),
        );
    }

    public function testTouchProductDeletedShouldTouchDeletedLogic(): void
    {
        $this->createNewProductAndAssertNoTouchExists();

        $this->productFacade->touchProductConcreteDelete($this->productAbstractTransfer->getIdProductAbstract());

        $this->tester->assertTouchDeleted(
            ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE,
            $this->productAbstractTransfer->getIdProductAbstract(),
        );
    }

    public function testGetProductConcretesBySkusShouldReturnProductConcretesTransfers(): void
    {
        $this->setupDefaultProducts();

        $productConcretesTransfers = $this->productFacade->findProductConcretesBySkus(
            [$this->productConcreteTransfer->getSku()],
        );

        $this->assertCreateProductConcrete($productConcretesTransfers[0]);
        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcretesTransfers[0]);
    }

    public function testGetProductConcretesBySkusShouldReturnEmptyArray(): void
    {
        $fakeNonExistSku = '101001101001';

        $this->setupDefaultProducts();

        $productConcreteTransfers = $this->productFacade->findProductConcretesBySkus([
            $fakeNonExistSku,
        ]);

        $this->assertEmpty($productConcreteTransfers);
    }

    public function testGetProductConcreteByIdShouldReturnConcreteTransfer(): void
    {
        $this->setupDefaultProducts();

        $productConcreteTransfer = $this->productFacade->findProductConcreteById(
            $this->productConcreteTransfer->getIdProductConcrete(),
        );

        $this->assertCreateProductConcrete($productConcreteTransfer);
        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransfer);
    }

    public function testGetProductConcreteCollectionByIdsShouldReturnConcreteTransfers(): void
    {
        // Arrange
        $this->setupDefaultProducts();

        // Act
        $productConcreteTransferCollection = $this->productConcreteManager->findProductConcreteByIds(
            [$this->productConcreteTransfer->getIdProductConcrete()],
        );

        // Assert
        $this->assertIsArray($productConcreteTransferCollection);
        $this->assertNotEmpty($productConcreteTransferCollection);
        $this->assertCreateProductConcrete($productConcreteTransferCollection[0]);
        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcreteTransferCollection[0]);
    }

    public function testGetProductConcreteByIdShouldReturnNull(): void
    {
        $productConcreteTransfer = $this->productFacade->findProductConcreteById(101001);

        $this->assertNull($productConcreteTransfer);
    }

    public function testGetProductConcreteCollectionByIdsShouldReturnEmptyArray(): void
    {
        // Act
        $productConcreteTransferCollection = $this->productConcreteManager->findProductConcreteByIds([101001]);

        // Assert
        $this->assertIsArray($productConcreteTransferCollection);
        $this->assertEmpty($productConcreteTransferCollection);
    }

    public function testGetProductConcreteIdBySkuShouldReturnId(): void
    {
        $this->setupDefaultProducts();

        $id = $this->productFacade->findProductConcreteIdBySku($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productConcreteTransfer->getIdProductConcrete(), $id);
    }

    public function testGetProductConcreteIdBySkuShouldReturnNull(): void
    {
        $id = $this->productFacade->findProductConcreteIdBySku('INVALIDSKU');

        $this->assertNull($id);
    }

    public function testGetProductConcreteShouldReturnConcreteTransfer(): void
    {
        $this->setupDefaultProducts();

        $productConcrete = $this->productFacade->getProductConcrete($this->productConcreteTransfer->getSku());

        $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
    }

    public function testGetProductConcreteShouldThrowException(): void
    {
        $this->expectException(MissingProductException::class);

        $this->productFacade->getProductConcrete('INVALIDSKU');
    }

    public function testGetConcreteProductsByAbstractProductIdShouldReturnConcreteCollection(): void
    {
        $this->setupDefaultProducts();

        $productConcreteCollection = $this->productFacade->getConcreteProductsByAbstractProductId(
            $this->productAbstractTransfer->getIdProductAbstract(),
        );

        foreach ($productConcreteCollection as $productConcrete) {
            $this->assertInstanceOf(ProductConcreteTransfer::class, $productConcrete);
            $this->assertEquals($this->productConcreteTransfer->getSku(), $productConcrete->getSku());
        }
    }

    public function testGetProductAbstractIdByConcreteSku(): void
    {
        $this->setupDefaultProducts();

        $idProductAbstract = $this->productFacade->getProductAbstractIdByConcreteSku($this->productConcreteTransfer->getSku());

        $this->assertEquals($this->productAbstractTransfer->getIdProductAbstract(), $idProductAbstract);
    }

    public function testGetProductAbstractIdByConcreteSkuShouldThrowException(): void
    {
        $this->expectException(MissingProductException::class);

        $this->setupDefaultProducts();

        $this->productFacade->getProductAbstractIdByConcreteSku('INVALIDSKU');
    }

    public function testGetConcreteProductsByAbstractProductIdShouldReturnEmptyArray(): void
    {
        $productConcreteCollection = $this->productFacade->getConcreteProductsByAbstractProductId(
            121231,
        );

        $this->assertEmpty($productConcreteCollection);
    }

    public function testGetLocalizedProductConcreteName(): void
    {
        $this->setupDefaultProducts();

        $productNameEN = $this->productFacade->getLocalizedProductConcreteName(
            $this->productConcreteTransfer,
            $this->locales['en_US'],
        );

        $productNameDE = $this->productFacade->getLocalizedProductConcreteName(
            $this->productConcreteTransfer,
            $this->locales['de_DE'],
        );

        $this->assertSame(static::PRODUCT_CONCRETE_NAME['en_US'], $productNameEN);
        $this->assertSame(static::PRODUCT_CONCRETE_NAME['de_DE'], $productNameDE);
    }

    protected function createNewProductAndAssertNoTouchExists(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $this->productConcreteTransfer->setFkProductAbstract($idProductAbstract);
        $idProductConcrete = $this->productConcreteManager->createProductConcrete($this->productConcreteTransfer);
        $this->productConcreteTransfer->setIdProductConcrete($idProductConcrete);

        $this->tester->assertNoTouchEntry(ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE, $this->productConcreteTransfer->getIdProductConcrete());
    }

    protected function assertCreateProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $createdProductEntity = $this->getProductConcreteEntityById($productConcreteTransfer->getIdProductConcrete());

        $this->assertNotNull($createdProductEntity);
        $this->assertEquals($productConcreteTransfer->getSku(), $createdProductEntity->getSku());
    }

    protected function assertSaveProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $updatedProductEntity = $this->getProductConcreteEntityById($productConcreteTransfer->getIdProductConcrete());

        $this->assertNotNull($updatedProductEntity);
        $this->assertEquals($this->productConcreteTransfer->getSku(), $updatedProductEntity->getSku());

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $expectedProductName = static::UPDATED_PRODUCT_ABSTRACT_NAME[$localizedAttribute->getLocale()->getLocaleName()];

            $this->assertSame($expectedProductName, $localizedAttribute->getName());
        }
    }

    protected function getProductConcreteEntityById(int $idProductConcrete): ?SpyProduct
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProductConcrete)
            ->findOne();
    }
}
