<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Product\Persistence\ProductPersistenceFactory getFactory()
 */
class ProductEntityManager extends AbstractEntityManager implements ProductEntityManagerInterface
{
    public function createProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer {
        $productEntityCollection = new ObjectCollection();
        $productEntityCollection->setModel(SpyProduct::class);

        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $productConcreteTransfer->requireSku()->requireFkProductAbstract();

            $productEntityCollection->append(
                $productMapper->mapProductConcreteTransferToProductEntity($productConcreteTransfer, new SpyProduct()),
            );
        }

        $productEntityCollection->save();

        return $productMapper->mapProductEntityCollectionPrimaryKeysToProductConcreteCollectionTransfer(
            $productEntityCollection,
            $productConcreteCollectionTransfer,
        );
    }

    public function createProductConcreteCollectionLocalizedAttributes(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        $productLocalizedAttributesEntityCollection = new ObjectCollection();
        $productLocalizedAttributesEntityCollection->setModel(SpyProductLocalizedAttributes::class);

        $localizedAttributesMapper = $this->getFactory()->createLocalizedAttributesMapper();

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $idProductConcrete = $productConcreteTransfer->getIdProductConcreteOrFail();

            foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
                $localizedAttributesTransfer->requireName()->requireLocale();

                $productLocalizedAttributesEntityCollection->append(
                    $localizedAttributesMapper->mapLocalizedAttributesTransferToProductLocalizedAttributesEntity(
                        $localizedAttributesTransfer,
                        (new SpyProductLocalizedAttributes())->setFkProduct($idProductConcrete),
                    ),
                );
            }
        }

        $productLocalizedAttributesEntityCollection->save();
    }

    public function updateProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        $productMapper = $this->getFactory()->createProductMapper();

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $productConcreteTransfer->requireIdProductConcrete()->requireSku()->requireFkProductAbstract();

            $productEntity = $this->getFactory()
                ->createProductQuery()
                ->filterByIdProduct($productConcreteTransfer->getIdProductConcreteOrFail())
                ->findOneOrCreate();

            $productMapper->mapProductConcreteTransferToProductEntity($productConcreteTransfer, $productEntity);

            $productEntity->save();
        }
    }

    public function updateProductConcreteCollectionLocalizedAttributes(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        $localizedAttributesMapper = $this->getFactory()->createLocalizedAttributesMapper();

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $idProductConcrete = $productConcreteTransfer->getIdProductConcreteOrFail();

            foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
                $localizedAttributesTransfer->requireName()->requireLocale();

                $productLocalizedAttributesEntity = $this->getFactory()
                    ->createProductLocalizedAttributesQuery()
                    ->filterByFkProduct($idProductConcrete)
                    ->filterByFkLocale($localizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail())
                    ->findOneOrCreate();

                $localizedAttributesMapper->mapLocalizedAttributesTransferToProductLocalizedAttributesEntity(
                    $localizedAttributesTransfer,
                    $productLocalizedAttributesEntity->setFkProduct($idProductConcrete),
                );

                $productLocalizedAttributesEntity->save();
            }
        }
    }
}
