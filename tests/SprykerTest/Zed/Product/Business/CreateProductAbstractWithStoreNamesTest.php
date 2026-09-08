<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractStoreQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group CreateProductAbstractWithStoreNamesTest
 *
 * Add your own group annotations below this line
 */
class CreateProductAbstractWithStoreNamesTest extends FacadeTestAbstract
{
    protected const string STORE_NAME_DE = 'DE';

    public function testCreateProductAbstractResolvesStoreRelationByStoreName(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->productAbstractTransfer->setSku(sprintf('pxm-store-abstract-%s', uniqid()));
        $this->productAbstractTransfer->setStoreRelation(
            (new StoreRelationTransfer())->addStores((new StoreTransfer())->setName(static::STORE_NAME_DE)),
        );

        // Act
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);

        // Assert
        $productAbstractStoreCount = SpyProductAbstractStoreQuery::create()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkStore($storeTransfer->getIdStore())
            ->count();
        $this->assertSame(1, $productAbstractStoreCount);
    }
}
