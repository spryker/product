<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Product\Persistence\ProductRepository;

class ProductMapper implements ProductMapperInterface
{
    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractEntityToProductAbstractTransferForSuggestion(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        $productAbstractTransfer->setIdProductAbstract($productAbstractEntity->getIdProductAbstract());
        $productAbstractTransfer->setName($productAbstractEntity->getVirtualColumn(ProductRepository::KEY_FILTERED_PRODUCTS_PRODUCT_NAME));
        $productAbstractTransfer->setSku($productAbstractEntity->getSku());

        return $productAbstractTransfer;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductAbstractEntityToProductAbstractTransferWithoutRelations(
        SpyProductAbstract $productAbstractEntity,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        return $productAbstractTransfer->fromArray($productAbstractEntity->toArray(), true);
    }
}
