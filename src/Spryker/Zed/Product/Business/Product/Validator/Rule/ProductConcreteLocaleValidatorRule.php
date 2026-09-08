<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Product\Validator\Rule;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionResponseTransfer;
use Spryker\Zed\Product\Business\Reader\LocaleReaderInterface;

class ProductConcreteLocaleValidatorRule extends AbstractProductValidatorRule implements ProductConcreteCollectionValidatorRuleInterface
{
    protected const string ERROR_MESSAGE_LOCALE_NOT_INSTALLED = 'Locale "%s" is not installed.';

    public function __construct(
        protected readonly LocaleReaderInterface $localeReader,
    ) {
    }

    public function validate(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer,
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): ProductConcreteCollectionResponseTransfer {
        $localeNamesBySku = [];

        foreach ($productConcreteCollectionRequestTransfer->getProducts() as $productConcreteTransfer) {
            $localeNames = array_values(array_unique(array_merge(
                $this->extractLocalizedAttributesLocaleNames($productConcreteTransfer->getLocalizedAttributes()),
                $this->extractImageSetLocaleNames($productConcreteTransfer->getImageSets()),
            )));

            if ($localeNames !== []) {
                $localeNamesBySku[(string)$productConcreteTransfer->getSku()] = $localeNames;
            }
        }

        $unknownLocaleNames = $this->localeReader->getMissingLocaleNames(
            $this->flattenNames($localeNamesBySku),
        );

        if ($unknownLocaleNames === []) {
            return $productConcreteCollectionResponseTransfer;
        }

        foreach ($localeNamesBySku as $sku => $localeNames) {
            foreach (array_intersect($localeNames, $unknownLocaleNames) as $unknownLocaleName) {
                $productConcreteCollectionResponseTransfer->addError(
                    $this->createError(
                        (string)$sku,
                        sprintf(static::ERROR_MESSAGE_LOCALE_NOT_INSTALLED, $unknownLocaleName),
                    ),
                );
            }
        }

        return $productConcreteCollectionResponseTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     *
     * @return array<string>
     */
    protected function extractImageSetLocaleNames(ArrayObject $productImageSetTransfers): array
    {
        $localeNames = [];

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $localeName = $productImageSetTransfer->getLocale()?->getLocaleName();

            if ($localeName !== null && $localeName !== '') {
                $localeNames[] = $localeName;
            }
        }

        return array_values(array_unique($localeNames));
    }
}
