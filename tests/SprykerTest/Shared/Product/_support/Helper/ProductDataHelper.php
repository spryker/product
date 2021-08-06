<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Product\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\LocalizedAttributesBuilder;
use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $productConcreteOverride
     * @param array $productAbstractOverride
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProduct(array $productConcreteOverride = [], array $productAbstractOverride = []): ProductConcreteTransfer
    {
        $productAbstractTransfer = (new ProductAbstractBuilder($productAbstractOverride))->build();

        $productFacade = $this->getProductFacade();
        $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);

        $productConcreteTransfer = (new ProductConcreteBuilder(['fkProductAbstract' => $abstractProductId]))
            ->seed($productConcreteOverride)
            ->build();

        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());
        $productFacade->createProductConcrete($productConcreteTransfer);

        $this->debug(sprintf(
            'Inserted AbstractProduct: %d, Concrete Product: %d',
            $abstractProductId,
            $productConcreteTransfer->getIdProductConcrete()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConcreteTransfer): void {
            $this->cleanupProductConcrete($productConcreteTransfer->getIdProductConcrete());
            $this->cleanupProductAbstract($productConcreteTransfer->getFkProductAbstract());
        });

        return $productConcreteTransfer;
    }

    /**
     * @param array $productConcreteOverride
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProductConcrete(array $productConcreteOverride = []): ProductConcreteTransfer
    {
        $productConcreteTransfer = (new ProductConcreteBuilder())
            ->seed($productConcreteOverride)
            ->build();

        $this->getProductFacade()->createProductConcrete($productConcreteTransfer);

        $this->debug(sprintf(
            'Inserted Concrete Product: %d',
            $productConcreteTransfer->getIdProductConcrete()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConcreteTransfer): void {
            $this->cleanupProductConcrete($productConcreteTransfer->getIdProductConcrete());
        });

        return $productConcreteTransfer;
    }

    /**
     * @param array $productAbstractOverride
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function haveProductAbstract(array $productAbstractOverride = []): ProductAbstractTransfer
    {
        $productAbstractTransfer = (new ProductAbstractBuilder($productAbstractOverride))->build();

        $productFacade = $this->getProductFacade();
        $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);

        $this->debug(sprintf(
            'Inserted AbstractProduct: %d',
            $abstractProductId
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productAbstractTransfer): void {
            $this->cleanupProductAbstract($productAbstractTransfer->getIdProductAbstract());
        });

        return $productAbstractTransfer;
    }

    /**
     * @param array $productConcreteOverride
     * @param array $productAbstractOverride
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveFullProduct(
        array $productConcreteOverride = [],
        array $productAbstractOverride = [],
        bool $useExistAbstractProductOrCreate = false
    ): ProductConcreteTransfer {
        $allStoresRelation = $this->getAllStoresRelation()->toArray();

        $localizedAttributes = (new LocalizedAttributesBuilder([
            LocalizedAttributesTransfer::NAME => uniqid('Product #', true),
            LocalizedAttributesTransfer::LOCALE => $this->getCurrentLocale(),
            LocalizedAttributesTransfer::ATTRIBUTES => $productConcreteOverride[ProductConcreteTransfer::ATTRIBUTES] ?? [],
        ]))->build()->toArray();

        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = (new ProductAbstractBuilder($productAbstractOverride))
            ->withLocalizedAttributes($localizedAttributes)
            ->withStoreRelation($allStoresRelation)
            ->build();

        $productFacade = $this->getProductFacade();

        $prevMessage = 'Inserted AbstractProduct';

        if ($useExistAbstractProductOrCreate) {
            if (
                isset($productAbstractOverride[ProductAbstractTransfer::SKU])
                && $productFacade->hasProductAbstract($productAbstractOverride[ProductAbstractTransfer::SKU])
            ) {
                $abstractProductId = $productFacade->findProductAbstractIdBySku(
                    $productAbstractOverride[ProductAbstractTransfer::SKU]
                );
                $prevMessage = 'Found AbstractProduct';
            } else if (
                isset($productAbstractOverride[ProductAbstractTransfer::ID_PRODUCT_ABSTRACT])
                && $productFacade->findProductAbstractById($productAbstractOverride[ProductAbstractTransfer::ID_PRODUCT_ABSTRACT]) !== null
            ) {
                $abstractProductId = $productAbstractOverride[ProductAbstractTransfer::ID_PRODUCT_ABSTRACT];
                $prevMessage = 'Found AbstractProduct';
            } else {
                $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);
            }
        } else {
            $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);
        }

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productAbstractTransfer */
        $productConcreteTransfer = (new ProductConcreteBuilder(array_merge(['fkProductAbstract' => $abstractProductId], $productConcreteOverride)))
            ->withLocalizedAttributes($localizedAttributes)
            ->withStores($allStoresRelation)
            ->build();
        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());

        $productFacade->createProductConcrete($productConcreteTransfer);

        $productFacade->updateProductUrl(
            $productAbstractTransfer->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
        );

        $this->debug(sprintf(
            '%s: %d, Inserted Concrete Product: %d',
            $prevMessage,
            $abstractProductId,
            $productConcreteTransfer->getIdProductConcrete()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConcreteTransfer): void {
            $this->cleanupProductConcrete($productConcreteTransfer->getIdProductConcrete());
            $this->cleanupProductAbstract($productConcreteTransfer->getFkProductAbstract());
        });

        return $productConcreteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function getAllStoresRelation(): StoreRelationTransfer
    {
        $stores = $this->getStoreFacade()->getAllStores();
        $idStores = array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $stores);

        return (new StoreRelationBuilder([
            StoreRelationTransfer::ID_STORES => $idStores,
            StoreRelationTransfer::STORES => new ArrayObject($stores),
        ]))->build();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getCurrentLocale(): LocaleTransfer
    {
        return $this->getLocaleFacade()->getCurrentLocale();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->getLocator()->locale()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributes
     *
     * @return void
     */
    public function addLocalizedAttributesToProductAbstract(ProductAbstractTransfer $productAbstractTransfer, array $localizedAttributes): void
    {
        $productAbstractTransfer->setLocalizedAttributes(
            new ArrayObject($localizedAttributes)
        );

        $this->getProductFacade()->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributes
     *
     * @return void
     */
    public function addLocalizedAttributesToProductConcrete(ProductConcreteTransfer $productConcreteTransfer, array $localizedAttributes): void
    {
        $productConcreteTransfer->setLocalizedAttributes(
            new ArrayObject($localizedAttributes)
        );

        $this->getProductFacade()->saveProductConcrete($productConcreteTransfer);
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    private function getProductFacade(): ProductFacadeInterface
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @return \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    private function getProductQuery(): ProductQueryContainerInterface
    {
        return $this->getLocator()->product()->queryContainer();
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    private function cleanupProductConcrete(int $idProductConcrete): void
    {
        $this->debug(sprintf('Deleting Concrete Product: %d', $idProductConcrete));

        $this->getProductQuery()
            ->queryProduct()
            ->findByIdProduct($idProductConcrete)
            ->delete();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    private function cleanupProductAbstract(int $idProductAbstract): void
    {
        $this->debug(sprintf('Deleting Abstract Product: %d', $idProductAbstract));

        $this->getProductQuery()
            ->queryProductAbstract()
            ->findByIdProductAbstract($idProductAbstract)
            ->delete();
    }
}
