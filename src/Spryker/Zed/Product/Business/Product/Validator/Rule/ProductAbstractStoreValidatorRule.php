<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Product\Validator\Rule;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionResponseTransfer;
use Spryker\Zed\Product\Business\Reader\StoreReaderInterface;

class ProductAbstractStoreValidatorRule extends AbstractProductValidatorRule implements ProductAbstractCollectionValidatorRuleInterface
{
    protected const string ERROR_MESSAGE_STORE_DOES_NOT_EXIST = 'Store "%s" does not exist.';

    public function __construct(
        protected readonly StoreReaderInterface $storeReader,
    ) {
    }

    public function validate(
        ProductAbstractCollectionRequestTransfer $productAbstractCollectionRequestTransfer,
        ProductAbstractCollectionResponseTransfer $productAbstractCollectionResponseTransfer
    ): ProductAbstractCollectionResponseTransfer {
        $storeNamesBySku = [];

        foreach ($productAbstractCollectionRequestTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $storeNames = $this->extractStoreNames(
                $productAbstractTransfer->getStoreRelation()?->getStores() ?? new ArrayObject(),
            );

            if ($storeNames !== []) {
                $storeNamesBySku[(string)$productAbstractTransfer->getSku()] = $storeNames;
            }
        }

        $unknownStoreNames = $this->storeReader->getMissingStoreNames(
            $this->flattenNames($storeNamesBySku),
        );

        if ($unknownStoreNames === []) {
            return $productAbstractCollectionResponseTransfer;
        }

        foreach ($storeNamesBySku as $sku => $storeNames) {
            foreach (array_intersect($storeNames, $unknownStoreNames) as $unknownStoreName) {
                $productAbstractCollectionResponseTransfer->addError(
                    $this->createError(
                        (string)$sku,
                        sprintf(static::ERROR_MESSAGE_STORE_DOES_NOT_EXIST, $unknownStoreName),
                    ),
                );
            }
        }

        return $productAbstractCollectionResponseTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<string>
     */
    protected function extractStoreNames(ArrayObject $storeTransfers): array
    {
        $storeNames = [];

        foreach ($storeTransfers as $storeTransfer) {
            $storeName = $storeTransfer->getName();

            if ($storeName !== null && $storeName !== '') {
                $storeNames[] = $storeName;
            }
        }

        return array_values(array_unique($storeNames));
    }
}
