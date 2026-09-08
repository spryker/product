<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Writer;

use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionResponseTransfer;

interface ProductConcreteCollectionCreatorInterface
{
    public function createProductCollection(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer
    ): ProductConcreteCollectionResponseTransfer;
}
