<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Reader;

use Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface;

class StoreReader implements StoreReaderInterface
{
    public function __construct(
        protected readonly ProductToStoreInterface $storeFacade,
    ) {
    }

    /**
     * @param array<string> $storeNames
     *
     * @return array<string>
     */
    public function getMissingStoreNames(array $storeNames): array
    {
        if ($storeNames === []) {
            return [];
        }

        return array_values(array_diff($storeNames, $this->getKnownStoreNames()));
    }

    /**
     * @return array<string>
     */
    protected function getKnownStoreNames(): array
    {
        $knownStoreNames = [];

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $storeName = $storeTransfer->getName();

            if ($storeName !== null && $storeName !== '') {
                $knownStoreNames[] = $storeName;
            }
        }

        return $knownStoreNames;
    }
}
