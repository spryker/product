<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Writer;

use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionResponseTransfer;

interface ProductAbstractCollectionCreatorInterface
{
    public function createProductAbstractCollection(
        ProductAbstractCollectionRequestTransfer $productAbstractCollectionRequestTransfer
    ): ProductAbstractCollectionResponseTransfer;
}
