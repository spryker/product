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
use Orm\Zed\Product\Persistence\SpyProductAbstractStoreQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
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
     * @var string
     */
    protected const LOCALE_US = 'en_US';

    /**
     * @param array $productConcreteOverride
     * @param array $productAbstractOverride
     * @param string|null $locale
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProduct(array $productConcreteOverride = [], array $productAbstractOverride = [], ?string $locale = null): ProductConcreteTransfer
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
            $productConcreteTransfer->getIdProductConcrete(),
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
            $productConcreteTransfer->getIdProductConcrete(),
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConcreteTransfer): void {
            $this->cleanupProductConcrete($productConcreteTransfer->getIdProductConcrete());
        });

        return $productConcreteTransfer;
    }

    /**
     * @param array $productAbstractOverride
     * @param bool $localized
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function haveProductAbstract(array $productAbstractOverride = [], bool $localized = false): ProductAbstractTransfer
    {
        $productAbstractTransfer = new ProductAbstractBuilder($productAbstractOverride);

        if ($localized) {
            $availableLocales = $this->getLocaleFacade()->getLocaleCollection();
            foreach ($availableLocales as $locale) {
                $localizedAttributes = (new LocalizedAttributesBuilder([
                    LocalizedAttributesTransfer::NAME => uniqid('Product #', true),
                    LocalizedAttributesTransfer::LOCALE => $locale,
                    LocalizedAttributesTransfer::ATTRIBUTES => $productAbstractOverride[ProductAbstractTransfer::ATTRIBUTES] ?? [],
                ]))->build()->toArray();

                $productAbstractTransfer->withLocalizedAttributes($localizedAttributes);
            }
        }

        $productAbstractTransfer = $productAbstractTransfer->build();

        $productFacade = $this->getProductFacade();
        $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);

        $this->debug(sprintf(
            'Inserted AbstractProduct: %d',
            $abstractProductId,
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
        array $productAbstractOverride = []
    ): ProductConcreteTransfer {
        $allStoresRelation = $this->getAllStoresRelation()->toArray();
        $localizedAttributes = $productAbstractOverride[ProductAbstractTransfer::LOCALIZED_ATTRIBUTES] ?? null;
        if ($localizedAttributes === null) {
            $localizedAttributes[] = (new LocalizedAttributesBuilder([
                LocalizedAttributesTransfer::NAME => uniqid('Product #', true),
                LocalizedAttributesTransfer::LOCALE => $productAbstractOverride[LocalizedAttributesTransfer::LOCALE] ?? $this->getCurrentLocale(),
                LocalizedAttributesTransfer::ATTRIBUTES => $productConcreteOverride[ProductConcreteTransfer::ATTRIBUTES] ?? [],
            ]))->build()->toArray();
        }
        $productAbstractBuilder = new ProductAbstractBuilder($productAbstractOverride);

        foreach ($localizedAttributes as $localizedAttribute) {
            $productAbstractBuilder->withLocalizedAttributes($localizedAttribute);
        }

        /** @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer */
        $productAbstractTransfer = $productAbstractBuilder->withStoreRelation($allStoresRelation)
            ->build();

        $productFacade = $this->getProductFacade();

        $abstractProductId = $productFacade->createProductAbstract($productAbstractTransfer);

        $productConcreteBuilder = new ProductConcreteBuilder(array_merge(['fkProductAbstract' => $abstractProductId], $productConcreteOverride));

        foreach ($localizedAttributes as $localizedAttribute) {
            $productConcreteBuilder->withLocalizedAttributes($localizedAttribute);
        }

        /** @var \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer */
        $productConcreteTransfer = $productConcreteBuilder->withStores($allStoresRelation)->build();
        $productConcreteTransfer->setAbstractSku($productAbstractTransfer->getSku());

        $productFacade->createProductConcrete($productConcreteTransfer);

        $productFacade->createProductUrl(
            $productAbstractTransfer->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract()),
        );

        $this->debug(sprintf(
            'Inserted AbstractProduct: %d, Concrete Product: %d',
            $abstractProductId,
            $productConcreteTransfer->getIdProductConcrete(),
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productConcreteTransfer): void {
            $this->cleanupProductConcrete($productConcreteTransfer->getIdProductConcrete());
            $this->cleanupProductAbstract($productConcreteTransfer->getFkProductAbstract());
        });

        return $productConcreteTransfer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<string>
     */
    public function getProductAbstractStoreNamesByIdProductAbstract(int $idProductAbstract): array
    {
        return SpyProductAbstractStoreQuery::create()
            ->filterByFkProductAbstract($idProductAbstract)
            ->innerJoinSpyStore()
            ->select(SpyStoreTableMap::COL_NAME)
            ->find()
            ->getData();
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
        if ((bool)getenv('SPRYKER_DYNAMIC_STORE_MODE') === false) {
            return $this->getLocaleFacade()->getCurrentLocale();
        }

        return $this->getLocaleFacade()->getLocale(static::LOCALE_US);
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
     * @param array<\Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     *
     * @return void
     */
    public function addLocalizedAttributesToProductAbstract(ProductAbstractTransfer $productAbstractTransfer, array $localizedAttributes): void
    {
        $productAbstractTransfer->setLocalizedAttributes(
            new ArrayObject($localizedAttributes),
        );

        $this->getProductFacade()->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array<\Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     *
     * @return void
     */
    public function addLocalizedAttributesToProductConcrete(ProductConcreteTransfer $productConcreteTransfer, array $localizedAttributes): void
    {
        $productConcreteTransfer->setLocalizedAttributes(
            new ArrayObject($localizedAttributes),
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
