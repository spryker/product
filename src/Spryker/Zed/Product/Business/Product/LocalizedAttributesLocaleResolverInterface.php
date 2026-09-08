<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use ArrayObject;

interface LocalizedAttributesLocaleResolverInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    public function resolveLocales(ArrayObject $localizedAttributesTransfers): ArrayObject;
}
