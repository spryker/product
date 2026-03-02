<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Status;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteStatusCheckerInterface
{
    public function isActive(ProductConcreteTransfer $productConcreteTransfer): bool;
}
