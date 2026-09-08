<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Product\Validator;

use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionResponseTransfer;

class ProductConcreteCollectionUpdateValidator implements ProductConcreteCollectionValidatorInterface
{
    /**
     * @param array<\Spryker\Zed\Product\Business\Product\Validator\Rule\ProductConcreteCollectionValidatorRuleInterface> $productConcreteCollectionValidatorRules
     * @param array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCollectionUpdateValidatorPluginInterface> $productConcreteCollectionUpdateValidatorPlugins
     */
    public function __construct(
        protected readonly array $productConcreteCollectionValidatorRules,
        protected readonly array $productConcreteCollectionUpdateValidatorPlugins,
    ) {
    }

    public function validate(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer
    ): ProductConcreteCollectionResponseTransfer {
        $productConcreteCollectionResponseTransfer = new ProductConcreteCollectionResponseTransfer();

        foreach ($productConcreteCollectionRequestTransfer->getProducts() as $productConcreteTransfer) {
            $productConcreteCollectionResponseTransfer->addProduct($productConcreteTransfer);
        }

        foreach ($this->productConcreteCollectionValidatorRules as $productConcreteCollectionValidatorRule) {
            $productConcreteCollectionResponseTransfer = $productConcreteCollectionValidatorRule->validate(
                $productConcreteCollectionRequestTransfer,
                $productConcreteCollectionResponseTransfer,
            );
        }

        foreach ($this->productConcreteCollectionUpdateValidatorPlugins as $productConcreteCollectionUpdateValidatorPlugin) {
            $productConcreteCollectionResponseTransfer = $productConcreteCollectionUpdateValidatorPlugin->validate(
                $productConcreteCollectionRequestTransfer,
                $productConcreteCollectionResponseTransfer,
            );
        }

        return $productConcreteCollectionResponseTransfer;
    }
}
