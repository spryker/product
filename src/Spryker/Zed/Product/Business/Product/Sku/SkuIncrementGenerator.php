<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Sku;

use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class SkuIncrementGenerator implements SkuIncrementGeneratorInterface
{
    protected const int SKU_READ_BATCH_SIZE = 1000;

    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
    }

    public function generateProductConcreteSkuIncrement(int $idProductAbstract): string
    {
        $productConcreteSkuMaxLastIncrementalValue = 1;
        $offset = 0;

        do {
            $productConcreteSkus = $this->productRepository->getProductConcreteSkusByAbstractProductId(
                $idProductAbstract,
                $offset,
                static::SKU_READ_BATCH_SIZE,
            );

            $productConcreteSkuMaxLastIncrementalValue = $this->resolveMaxIncrementalValue(
                $productConcreteSkus,
                $productConcreteSkuMaxLastIncrementalValue,
            );

            $offset += static::SKU_READ_BATCH_SIZE;
        } while (count($productConcreteSkus) === static::SKU_READ_BATCH_SIZE);

        return (string)$productConcreteSkuMaxLastIncrementalValue;
    }

    /**
     * @param array<string> $productConcreteSkus
     * @param int $productConcreteSkuMaxLastIncrementalValue
     *
     * @return int
     */
    protected function resolveMaxIncrementalValue(array $productConcreteSkus, int $productConcreteSkuMaxLastIncrementalValue): int
    {
        foreach ($productConcreteSkus as $productConcreteSku) {
            $productConcreteSkuLastValue = $this->getProductConcreteSkuLastPartIncremented($productConcreteSku);

            if ($productConcreteSkuLastValue > $productConcreteSkuMaxLastIncrementalValue) {
                $productConcreteSkuMaxLastIncrementalValue = $productConcreteSkuLastValue;
            }
        }

        return $productConcreteSkuMaxLastIncrementalValue;
    }

    protected function getProductConcreteSkuLastPartIncremented(string $productConcreteSku): int
    {
        if (mb_strpos($productConcreteSku, SkuGenerator::SKU_ABSTRACT_SEPARATOR) === false) {
            return 0;
        }

        $productConcreteSku = mb_substr($productConcreteSku, mb_strpos($productConcreteSku, SkuGenerator::SKU_ABSTRACT_SEPARATOR) + 1);

        if (!is_numeric($productConcreteSku) || (int)$productConcreteSku < 0) {
            return 0;
        }

        return (int)$productConcreteSku + 1;
    }
}
