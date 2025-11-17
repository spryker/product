<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Provider\ProductReadiness;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductReadinessTransfer;

class IsActiveConcreteProductReadinessProvider implements ProductConcreteReadinessProviderInterface
{
    /**
     * @var string
     */
    protected const TITLE_STATUS_IS_ACTIVE = 'Status is active';

    /**
     * @var string
     */
    protected const VALUE_YES = 'Yes';

    /**
     * @var string
     */
    protected const VALUE_NO = 'No';

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer $productConcreteReadinessRequestTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer> $productReadinessTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductReadinessTransfer>
     */
    public function provide(
        ProductConcreteReadinessRequestTransfer $productConcreteReadinessRequestTransfer,
        ArrayObject $productReadinessTransfers
    ): ArrayObject {
        if (!$productConcreteReadinessRequestTransfer->getProductConcrete()) {
            return $productReadinessTransfers;
        }

        $productReadinessTransfers->append(
            (new ProductReadinessTransfer())
                ->setTitle(static::TITLE_STATUS_IS_ACTIVE)
                ->addValue($productConcreteReadinessRequestTransfer->getProductConcrete()->getIsActive() ? static::VALUE_YES : static::VALUE_NO),
        );

        return $productReadinessTransfers;
    }
}
