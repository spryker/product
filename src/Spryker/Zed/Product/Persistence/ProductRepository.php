<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductRepository extends AbstractRepository implements ProductRepositoryInterface
{
    public const KEY_FILTERED_PRODUCTS_PRODUCT_NAME = 'name';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isProductConcreteActive(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        return $this->getFactory()
            ->createProductQuery()
            ->findOneBySku($productConcreteTransfer->getSku())
            ->getIsActive();
    }

    /**
     * @param string $search
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer
     */
    public function getProductAbstractSuggestionCollectionBySkuOrLocalizedName(
        string $search,
        PaginationTransfer $paginationTransfer,
        LocaleTransfer $localeTransfer
    ): ProductAbstractSuggestionCollectionTransfer {
        $criteria = new Criteria();
        $skuLikeCriteria = $criteria->getNewCriterion(
            SpyProductAbstractTableMap::COL_SKU,
            '%' . $search . '%',
            Criteria::LIKE
        );

        $productAbstractQuery = $this->getFactory()
            ->createProductAbstractQuery()
            ->leftJoinSpyProductAbstractLocalizedAttributes()
            ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse()
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::KEY_FILTERED_PRODUCTS_PRODUCT_NAME)
            ->where('lower(' . SpyProductAbstractLocalizedAttributesTableMap::COL_NAME . ') like ?', '%' . mb_strtolower($search) . '%')
            ->addOr($skuLikeCriteria)
            ->addAscendingOrderByColumn(SpyProductAbstractTableMap::COL_SKU);

        $paginationModel = $this->getPaginationModelFromQuery($productAbstractQuery, $paginationTransfer);
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $productAbstractQuery = $paginationModel->getQuery();

        $productAbstractEntities = $productAbstractQuery->find();

        return (new ProductAbstractSuggestionCollectionTransfer())
            ->setPagination($paginationTransfer)
            ->setProductAbstracts(
                $this->getProductAbstractTransfersMappedFromProductAbstractEntities($productAbstractEntities)
            );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $spyProductAbstractQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\Util\PropelModelPager
     */
    protected function getPaginationModelFromQuery(
        SpyProductAbstractQuery $spyProductAbstractQuery,
        PaginationTransfer $paginationTransfer
    ): PropelModelPager {
        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        return $spyProductAbstractQuery->paginate($page, $maxPerPage);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productAbstractEntities
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    protected function getProductAbstractTransfersMappedFromProductAbstractEntities(ObjectCollection $productAbstractEntities): ArrayObject
    {
        $productAbstractTransfers = new ArrayObject();
        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productAbstractEntities as $productAbstractEntity) {
            $productAbstractTransfers[] = $productMapper->mapProductAbstractEntityToProductAbstractTransferForSuggestion(
                $productAbstractEntity,
                new ProductAbstractTransfer()
            );
        }

        return $productAbstractTransfers;
    }
}
