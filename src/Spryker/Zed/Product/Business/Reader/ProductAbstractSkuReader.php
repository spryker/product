<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Reader;

use Generated\Shared\Transfer\ProductAbstractConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductAbstractSkuReader implements ProductAbstractSkuReaderInterface
{
    public function __construct(
        protected readonly ProductRepositoryInterface $productRepository,
    ) {
    }

    /**
     * @param array<string> $skus
     *
     * @return array<string, true>
     */
    public function getExistingSkus(array $skus): array
    {
        if ($skus === []) {
            return [];
        }

        $productAbstractConditionsTransfer = new ProductAbstractConditionsTransfer();

        foreach ($skus as $sku) {
            $productAbstractConditionsTransfer->addSku($sku);
        }

        $productAbstractCriteriaTransfer = (new ProductAbstractCriteriaTransfer())
            ->setProductAbstractConditions($productAbstractConditionsTransfer);

        $existingSkus = [];

        foreach ($this->productRepository->getProductAbstractCollection($productAbstractCriteriaTransfer)->getProductAbstracts() as $productAbstractTransfer) {
            $sku = $productAbstractTransfer->getSku();

            if ($sku !== null) {
                $existingSkus[$sku] = true;
            }
        }

        return $existingSkus;
    }
}
