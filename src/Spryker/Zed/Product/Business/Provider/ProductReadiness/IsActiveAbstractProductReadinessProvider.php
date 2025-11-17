<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Provider\ProductReadiness;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductReadinessTransfer;

class IsActiveAbstractProductReadinessProvider implements ProductAbstractReadinessProviderInterface
{
    /**
     * @var string
     */
    protected const TITLE_AT_LEAST_ONE_CONCRETE_IS_ACTIVE = 'At least one concrete is active';

    /**
     * @var string
     */
    protected const VALUE_YES = 'Yes';

    /**
     * @var string
     */
    protected const VALUE_NO = 'No';

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer $productAbstractReadinessRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer> $productReadinessTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>
     */
    public function provide(
        ProductAbstractReadinessRequestTransfer $productAbstractReadinessRequestTransfer,
        ArrayObject $productReadinessTransfers
    ): ArrayObject {
        $productReadinessTransfers->append(
            (new ProductReadinessTransfer())
                ->setTitle(static::TITLE_AT_LEAST_ONE_CONCRETE_IS_ACTIVE)
                ->addValue($this->hasActiveConcreteProducts($productAbstractReadinessRequestTransfer->getProductConcretes()) ? static::VALUE_YES : static::VALUE_NO),
        );

        return $productReadinessTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return bool
     */
    protected function hasActiveConcreteProducts(ArrayObject $productConcreteTransfers): bool
    {
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if ($productConcreteTransfer->getIsActive()) {
                return true;
            }
        }

        return false;
    }
}
