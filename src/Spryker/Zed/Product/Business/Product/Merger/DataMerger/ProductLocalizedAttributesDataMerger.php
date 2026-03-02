<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Merger\DataMerger;

use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductLocalizedAttributesDataMerger extends AbstractProductDataMerger
{
    protected function doMerge(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductConcreteTransfer {
        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $productConcreteLocalizedAttributesTransfer = $this->getProductConcreteLocalizedAttributesByLocale(
                $productConcreteTransfer,
                $localizedAttributesTransfer,
            );

            if ($productConcreteLocalizedAttributesTransfer !== null) {
                $this->mergeLocalizedAttributesData($productConcreteLocalizedAttributesTransfer, $localizedAttributesTransfer);
            } else {
                $productConcreteTransfer->addLocalizedAttributes($localizedAttributesTransfer);
            }
        }

        return $productConcreteTransfer;
    }

    protected function getProductConcreteLocalizedAttributesByLocale(
        ProductConcreteTransfer $productConcreteTransfer,
        LocalizedAttributesTransfer $localizedAttributesTransfer
    ): ?LocalizedAttributesTransfer {
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $productConcreteLocalizedAttributeTransfer) {
            if (
                $productConcreteLocalizedAttributeTransfer->getLocale()->getIdLocale()
                === $localizedAttributesTransfer->getLocale()->getIdLocale()
            ) {
                return $productConcreteLocalizedAttributeTransfer;
            }
        }

        return null;
    }

    protected function mergeLocalizedAttributesData(
        LocalizedAttributesTransfer $productConcreteLocalizedAttributesTransfer,
        LocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer
    ): void {
        $concreteAttributes = array_merge(
            array_filter($productAbstractLocalizedAttributesTransfer->toArray()),
            array_filter($productConcreteLocalizedAttributesTransfer->toArray()),
        );

        $productConcreteLocalizedAttributesTransfer->fromArray($concreteAttributes, true);

        $productConcreteLocalizedAttributesTransfer->setAttributes(
            array_merge(
                $productAbstractLocalizedAttributesTransfer->getAttributes(),
                $productConcreteLocalizedAttributesTransfer->getAttributes(),
            ),
        );

        if ($productConcreteLocalizedAttributesTransfer->getIsSearchable() === null) {
            $productConcreteLocalizedAttributesTransfer->setIsSearchable($productAbstractLocalizedAttributesTransfer->getIsSearchable());
        }
    }
}
