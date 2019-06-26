<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer;

interface ProductRepositoryInterface
{
    /**
     * @param string $search
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer
     */
    public function getProductAbstractSuggestionCollectionBySkuOrLocalizedName(
        string $search,
        PaginationTransfer $paginationTransfer,
        LocaleTransfer $localeTransfer
    ): ProductAbstractSuggestionCollectionTransfer;
}
