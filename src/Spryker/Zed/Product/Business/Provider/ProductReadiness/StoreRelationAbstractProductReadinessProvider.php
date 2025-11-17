<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Provider\ProductReadiness;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductReadinessTransfer;
use Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface;

class StoreRelationAbstractProductReadinessProvider implements ProductAbstractReadinessProviderInterface
{
    /**
     * @var string
     */
    protected const TITLE_HAS_RELATION_WITH_STORES = 'Has relation with stores';

    /**
     * @var string
     */
    protected const TITLE_HAS_NO_RELATION_WITH_STORES = 'Has no relation with stores';

    public function __construct(
        protected ProductToStoreInterface $storeFacade
    ) {
    }

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
        if (!$productAbstractReadinessRequestTransfer->getProductAbstract()) {
            return $productReadinessTransfers;
        }

        $hasRelationProductReadinessTransfer = (new ProductReadinessTransfer())->setTitle(static::TITLE_HAS_RELATION_WITH_STORES);

        $hasNoRelationProductReadinessTransfer = (new ProductReadinessTransfer())->setTitle(static::TITLE_HAS_NO_RELATION_WITH_STORES);

        $storeNames = $this->getStoreNames($productAbstractReadinessRequestTransfer->getProductAbstract()->getStoreRelation()->getStores());

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            if (in_array($storeTransfer->getName(), $storeNames)) {
                $hasRelationProductReadinessTransfer->addValue($storeTransfer->getName());

                continue;
            }
            $hasNoRelationProductReadinessTransfer->addValue($storeTransfer->getName());
        }

        $productReadinessTransfers->append($hasRelationProductReadinessTransfer);
        $productReadinessTransfers->append($hasNoRelationProductReadinessTransfer);

        return $productReadinessTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<string>
     */
    protected function getStoreNames(ArrayObject $storeTransfers): array
    {
        $storeNames = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeNames[] = $storeTransfer->getName();
        }

        return $storeNames;
    }
}
