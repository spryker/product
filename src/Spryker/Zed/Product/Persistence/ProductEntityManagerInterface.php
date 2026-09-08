<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;

interface ProductEntityManagerInterface
{
    public function createProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): ProductConcreteCollectionTransfer;

    public function createProductConcreteCollectionLocalizedAttributes(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void;

    public function updateProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void;

    public function updateProductConcreteCollectionLocalizedAttributes(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void;
}
