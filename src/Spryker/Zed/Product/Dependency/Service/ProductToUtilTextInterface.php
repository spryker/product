<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Service;

interface ProductToUtilTextInterface
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function generateSlug(string $value): string;
}
