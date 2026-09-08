<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group CreateProductConcreteWithReferencesTest
 * Add your own group annotations below this line
 */
class CreateProductConcreteWithReferencesTest extends FacadeTestAbstract
{
    protected const string CONCRETE_SKU_WITH_ABSTRACT_SKU = 'foo-concrete-abstract-sku';

    protected const string CONCRETE_SKU_WITH_FK = 'foo-concrete-fk';

    protected const string CONCRETE_SKU_LOCALE_NAME = 'foo-concrete-locale-name';

    protected const string CONCRETE_SKU_LOCALE_ID = 'foo-concrete-locale-id';

    protected const string CONCRETE_SKU_LOCALE_SET_BACK = 'foo-concrete-locale-set-back';

    protected const string CONCRETE_SKU_LOCALE_NULL = 'foo-concrete-locale-null';

    protected const string CONCRETE_SKU_LOCALE_MISSING = 'foo-concrete-locale-missing';

    protected const string CONCRETE_SKU_LOCALE_NOT_FOUND = 'foo-concrete-locale-not-found';

    protected const string NON_EXISTENT_LOCALE_NAME = 'xx_XX';

    public function testCreateProductConcreteResolvesAbstractIdByAbstractSku(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $productConcreteTransfer = $this->createProductConcreteTransferWithLocalizedAttributes(static::CONCRETE_SKU_WITH_ABSTRACT_SKU);
        $productConcreteTransfer->setAbstractSku(static::ABSTRACT_SKU);

        // Act
        $idProductConcrete = $this->productFacade->createProductConcrete($productConcreteTransfer);

        // Assert
        $this->assertGreaterThan(0, $idProductConcrete);
        $this->assertSame($idProductAbstract, $productConcreteTransfer->getFkProductAbstract());

        $productConcreteEntity = $this->getProductConcreteEntityById($idProductConcrete);
        $this->assertNotNull($productConcreteEntity);
        $this->assertSame($idProductAbstract, $productConcreteEntity->getFkProductAbstract());
    }

    public function testCreateProductConcreteWithFkProductAbstractStillWorks(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $productConcreteTransfer = $this->createProductConcreteTransferWithLocalizedAttributes(static::CONCRETE_SKU_WITH_FK);
        $productConcreteTransfer->setFkProductAbstract($idProductAbstract);

        // Act
        $idProductConcrete = $this->productFacade->createProductConcrete($productConcreteTransfer);

        // Assert
        $this->assertGreaterThan(0, $idProductConcrete);

        $productConcreteEntity = $this->getProductConcreteEntityById($idProductConcrete);
        $this->assertNotNull($productConcreteEntity);
        $this->assertSame($idProductAbstract, $productConcreteEntity->getFkProductAbstract());
    }

    public function testCreateProductConcreteResolvesLocaleByLocaleName(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $productConcreteTransfer = $this->createProductConcreteTransferWithLocaleNames(static::CONCRETE_SKU_LOCALE_NAME);
        $productConcreteTransfer->setFkProductAbstract($idProductAbstract);

        // Act
        $idProductConcrete = $this->productFacade->createProductConcrete($productConcreteTransfer);

        // Assert
        $this->assertGreaterThan(0, $idProductConcrete);

        $productConcreteEntity = $this->getProductConcreteEntityById($idProductConcrete);
        $this->assertNotNull($productConcreteEntity);
        $this->assertSame(static::CONCRETE_SKU_LOCALE_NAME, $productConcreteEntity->getSku());

        $localizedAttributeEntities = $this->productQueryContainer
            ->queryProductConcreteAttributeCollection($idProductConcrete, $this->locales[static::DE_LOCALE]->getIdLocale())
            ->find();

        $this->assertCount(1, $localizedAttributeEntities);
    }

    public function testCreateProductConcreteWithIdLocaleStillWorks(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $productConcreteTransfer = $this->createProductConcreteTransferWithLocalizedAttributes(static::CONCRETE_SKU_LOCALE_ID);
        $productConcreteTransfer->setFkProductAbstract($idProductAbstract);

        // Act
        $idProductConcrete = $this->productFacade->createProductConcrete($productConcreteTransfer);

        // Assert
        $this->assertGreaterThan(0, $idProductConcrete);

        $localizedAttributeEntities = $this->productQueryContainer
            ->queryProductConcreteAttributeCollection($idProductConcrete, $this->locales[static::EN_LOCALE]->getIdLocale())
            ->find();

        $this->assertCount(1, $localizedAttributeEntities);
    }

    public function testCreateProductConcreteSetsResolvedLocaleBackOnTransfer(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $productConcreteTransfer = $this->createProductConcreteTransferWithLocaleNames(static::CONCRETE_SKU_LOCALE_SET_BACK);
        $productConcreteTransfer->setFkProductAbstract($idProductAbstract);

        // Act
        $this->productFacade->createProductConcrete($productConcreteTransfer);

        // Assert
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $localeTransfer = $localizedAttributesTransfer->getLocale();

            $this->assertNotNull($localeTransfer);
            $this->assertNotNull($localeTransfer->getIdLocale(), 'idLocale should be resolved and set back on the transfer');
            $this->assertNotNull($localeTransfer->getLocaleName());
        }
    }

    public function testCreateProductConcreteThrowsExceptionWhenLocaleIsNull(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setSku(static::CONCRETE_SKU_LOCALE_NULL)
            ->setIsActive(true)
            ->setFkProductAbstract($idProductAbstract);

        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->setName(static::PRODUCT_CONCRETE_NAME[static::EN_LOCALE]);

        $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->productFacade->createProductConcrete($productConcreteTransfer);
    }

    public function testCreateProductConcreteThrowsExceptionWhenBothIdLocaleAndLocaleNameMissing(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $localeTransfer = new LocaleTransfer();

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setSku(static::CONCRETE_SKU_LOCALE_MISSING)
            ->setIsActive(true)
            ->setFkProductAbstract($idProductAbstract);

        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->setName(static::PRODUCT_CONCRETE_NAME[static::EN_LOCALE])
            ->setLocale($localeTransfer);

        $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->productFacade->createProductConcrete($productConcreteTransfer);
    }

    public function testCreateProductConcreteThrowsExceptionWhenLocaleNameNotFound(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        $localeTransfer = (new LocaleTransfer())
            ->setLocaleName(static::NON_EXISTENT_LOCALE_NAME);

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setSku(static::CONCRETE_SKU_LOCALE_NOT_FOUND)
            ->setIsActive(true)
            ->setFkProductAbstract($idProductAbstract);

        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->setName(static::PRODUCT_CONCRETE_NAME[static::EN_LOCALE])
            ->setLocale($localeTransfer);

        $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->productFacade->createProductConcrete($productConcreteTransfer);
    }

    protected function createProductConcreteTransferWithLocalizedAttributes(string $sku): ProductConcreteTransfer
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setSku($sku)
            ->setIsActive(true);

        foreach ($this->locales as $localeName => $localeTransfer) {
            $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
                ->setName(static::PRODUCT_CONCRETE_NAME[$localeName])
                ->setLocale($localeTransfer);

            $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productConcreteTransfer;
    }

    protected function createProductConcreteTransferWithLocaleNames(string $sku): ProductConcreteTransfer
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setSku($sku)
            ->setIsActive(true);

        foreach ($this->locales as $localeName => $localeTransfer) {
            $localeByNameOnly = (new LocaleTransfer())
                ->setLocaleName($localeName);

            $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
                ->setName(static::PRODUCT_CONCRETE_NAME[$localeName])
                ->setLocale($localeByNameOnly);

            $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
        }

        return $productConcreteTransfer;
    }

    protected function getProductConcreteEntityById(int $idProductConcrete): ?SpyProduct
    {
        return $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($idProductConcrete)
            ->findOne();
    }
}
