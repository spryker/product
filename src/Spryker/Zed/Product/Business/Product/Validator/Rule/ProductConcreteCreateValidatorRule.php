<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Product\Validator\Rule;

use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;

class ProductConcreteCreateValidatorRule extends AbstractProductValidatorRule implements ProductConcreteCollectionValidatorRuleInterface
{
    public function __construct(
        protected readonly ProductConcreteManagerInterface $productConcreteManager,
        protected readonly ProductAbstractManagerInterface $productAbstractManager,
    ) {
    }

    public function validate(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer,
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): ProductConcreteCollectionResponseTransfer {
        foreach ($productConcreteCollectionRequestTransfer->getProducts() as $productConcreteTransfer) {
            $this->validateProductConcrete($productConcreteTransfer, $productConcreteCollectionResponseTransfer);
        }

        return $productConcreteCollectionResponseTransfer;
    }

    protected function validateProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): void {
        $sku = $productConcreteTransfer->getSku();

        if ($sku !== null && $this->productConcreteManager->hasProductConcrete($sku)) {
            $productConcreteCollectionResponseTransfer->addError(
                $this->createError($sku, sprintf('Concrete product with SKU "%s" already exists.', $sku)),
            );
        }

        $abstractSku = $productConcreteTransfer->getAbstractSku();

        if ($abstractSku === null && $productConcreteTransfer->getFkProductAbstract() === null) {
            $productConcreteCollectionResponseTransfer->addError(
                $this->createError($sku, 'Field "abstractSku" is required when no abstract product is referenced.'),
            );
        }

        if ($abstractSku !== null && !$this->productAbstractManager->hasProductAbstract($abstractSku)) {
            $productConcreteCollectionResponseTransfer->addError(
                $this->createError($sku, sprintf('Abstract product with SKU "%s" does not exist.', $abstractSku)),
            );
        }

        if ($this->hasLocalizedAttributesWithoutLocaleName($productConcreteTransfer->getLocalizedAttributes())) {
            $productConcreteCollectionResponseTransfer->addError(
                $this->createError($sku, 'Field "localeName" is required for every localized attributes entry.'),
            );
        }

        if ($this->isRangeInverted($productConcreteTransfer->getValidFrom(), $productConcreteTransfer->getValidTo())) {
            $productConcreteCollectionResponseTransfer->addError(
                $this->createError($sku, 'Field "validFrom" must not be later than "validTo".'),
            );
        }
    }
}
