<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Product\Validator\Rule;

use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Reader\ProductAbstractSkuReaderInterface;

class ProductAbstractCreateValidatorRule extends AbstractProductValidatorRule implements ProductAbstractCollectionValidatorRuleInterface
{
    public function __construct(
        protected readonly ProductAbstractSkuReaderInterface $productAbstractSkuReader,
    ) {
    }

    public function validate(
        ProductAbstractCollectionRequestTransfer $productAbstractCollectionRequestTransfer,
        ProductAbstractCollectionResponseTransfer $productAbstractCollectionResponseTransfer
    ): ProductAbstractCollectionResponseTransfer {
        $existingSkus = $this->productAbstractSkuReader->getExistingSkus(
            $this->extractProductAbstractSkus($productAbstractCollectionRequestTransfer->getProductAbstracts()),
        );

        foreach ($productAbstractCollectionRequestTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $this->validateProductAbstract($productAbstractTransfer, $existingSkus, $productAbstractCollectionResponseTransfer);
        }

        return $productAbstractCollectionResponseTransfer;
    }

    /**
     * @param array<string, true> $existingSkus
     */
    protected function validateProductAbstract(
        ProductAbstractTransfer $productAbstractTransfer,
        array $existingSkus,
        ProductAbstractCollectionResponseTransfer $productAbstractCollectionResponseTransfer
    ): void {
        $sku = $productAbstractTransfer->getSku();

        if ($sku !== null && isset($existingSkus[$sku])) {
            $productAbstractCollectionResponseTransfer->addError(
                $this->createError($sku, sprintf('Abstract product with SKU "%s" already exists.', $sku)),
            );
        }

        if ($this->hasLocalizedAttributesWithoutLocaleName($productAbstractTransfer->getLocalizedAttributes())) {
            $productAbstractCollectionResponseTransfer->addError(
                $this->createError($sku, 'Field "localeName" is required for every localized attributes entry.'),
            );
        }

        if ($this->isRangeInverted($productAbstractTransfer->getNewFrom(), $productAbstractTransfer->getNewTo())) {
            $productAbstractCollectionResponseTransfer->addError(
                $this->createError($sku, 'Field "newFrom" must not be later than "newTo".'),
            );
        }
    }
}
