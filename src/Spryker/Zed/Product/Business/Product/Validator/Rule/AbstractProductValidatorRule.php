<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Product\Validator\Rule;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;

abstract class AbstractProductValidatorRule
{
    protected function createError(?string $entityIdentifier, string $message): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setEntityIdentifier($entityIdentifier)
            ->setMessage($message);
    }

    protected function isRangeInverted(?string $from, ?string $to): bool
    {
        if ($from === null || $to === null) {
            return false;
        }

        $fromTimestamp = strtotime($from);
        $toTimestamp = strtotime($to);

        if ($fromTimestamp === false || $toTimestamp === false) {
            return false;
        }

        return $fromTimestamp > $toTimestamp;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     */
    protected function hasLocalizedAttributesWithoutLocaleName(ArrayObject $localizedAttributesTransfers): bool
    {
        foreach ($localizedAttributesTransfers as $localizedAttributesTransfer) {
            $localeName = $localizedAttributesTransfer->getLocale()?->getLocaleName();

            if ($localeName === null || $localeName === '') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @return array<string>
     */
    protected function extractLocalizedAttributesLocaleNames(ArrayObject $localizedAttributesTransfers): array
    {
        $localeNames = [];

        foreach ($localizedAttributesTransfers as $localizedAttributesTransfer) {
            $localeName = $localizedAttributesTransfer->getLocale()?->getLocaleName();

            if ($localeName !== null && $localeName !== '') {
                $localeNames[] = $localeName;
            }
        }

        return array_values(array_unique($localeNames));
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return array<string>
     */
    protected function extractProductAbstractSkus(ArrayObject $productAbstractTransfers): array
    {
        $skus = [];

        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $sku = $productAbstractTransfer->getSku();

            if ($sku !== null) {
                $skus[] = $sku;
            }
        }

        return array_values(array_unique($skus));
    }

    /**
     * @param array<string, array<string>> $namesByEntityIdentifier
     *
     * @return array<string>
     */
    protected function flattenNames(array $namesByEntityIdentifier): array
    {
        $names = [];

        foreach ($namesByEntityIdentifier as $entityNames) {
            $names = array_merge($names, $entityNames);
        }

        return array_values(array_unique($names));
    }
}
