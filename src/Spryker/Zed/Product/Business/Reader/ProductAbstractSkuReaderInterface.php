<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Reader;

interface ProductAbstractSkuReaderInterface
{
    /**
     * @param array<string> $skus
     *
     * @return array<string, true>
     */
    public function getExistingSkus(array $skus): array;
}
