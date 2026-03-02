<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Propel\Runtime\Collection\Collection;

interface ProductMapperInterface
{
    public function mapProductConcreteEntityToTransfer(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer;

    public function mapProductAbstractEntityToProductAbstractTransferForSuggestion(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer;

    public function mapProductEntityToProductConcreteTransferWithoutStores(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer;

    public function mapProductConcreteEntityToProductConcreteTransferWithoutRelations(
        SpyProduct $productEntity,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer;

    public function mapProductAbstractEntityToProductAbstractTransferWithoutRelations(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer;

    public function mapProductConcreteTransferToProductEntity(
        ProductConcreteTransfer $productConcreteTransfer,
        SpyProduct $productEntity
    ): SpyProduct;

    public function mapProductEntityCollectionPrimaryKeysToProductConcreteCollectionTransfer(
        Collection $productEntityCollection,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Product\Persistence\SpyProductAbstract> $productAbstractCollection
     * @param \Generated\Shared\Transfer\ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function mapProductAbstractEntitiesToProductAbstractCollectionTransfer(
        Collection $productAbstractCollection,
        ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
    ): ProductAbstractCollectionTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\Product\Persistence\SpyProduct> $productEntities
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function mapProductEntitiesToProductConcreteCollection(
        Collection $productEntities,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer;
}
