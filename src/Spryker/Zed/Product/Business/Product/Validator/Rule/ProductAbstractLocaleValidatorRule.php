<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Product\Validator\Rule;

use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionResponseTransfer;
use Spryker\Zed\Product\Business\Reader\LocaleReaderInterface;

class ProductAbstractLocaleValidatorRule extends AbstractProductValidatorRule implements ProductAbstractCollectionValidatorRuleInterface
{
    protected const string ERROR_MESSAGE_LOCALE_NOT_INSTALLED = 'Locale "%s" is not installed.';

    public function __construct(
        protected readonly LocaleReaderInterface $localeReader,
    ) {
    }

    public function validate(
        ProductAbstractCollectionRequestTransfer $productAbstractCollectionRequestTransfer,
        ProductAbstractCollectionResponseTransfer $productAbstractCollectionResponseTransfer
    ): ProductAbstractCollectionResponseTransfer {
        $localeNamesBySku = [];

        foreach ($productAbstractCollectionRequestTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $localeNames = $this->extractLocalizedAttributesLocaleNames($productAbstractTransfer->getLocalizedAttributes());

            if ($localeNames !== []) {
                $localeNamesBySku[(string)$productAbstractTransfer->getSku()] = $localeNames;
            }
        }

        $unknownLocaleNames = $this->localeReader->getMissingLocaleNames(
            $this->flattenNames($localeNamesBySku),
        );

        if ($unknownLocaleNames === []) {
            return $productAbstractCollectionResponseTransfer;
        }

        foreach ($localeNamesBySku as $sku => $localeNames) {
            foreach (array_intersect($localeNames, $unknownLocaleNames) as $unknownLocaleName) {
                $productAbstractCollectionResponseTransfer->addError(
                    $this->createError(
                        (string)$sku,
                        sprintf(static::ERROR_MESSAGE_LOCALE_NOT_INSTALLED, $unknownLocaleName),
                    ),
                );
            }
        }

        return $productAbstractCollectionResponseTransfer;
    }
}
