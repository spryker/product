<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Product\Validator;

use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionResponseTransfer;

class ProductAbstractCollectionUpdateValidator implements ProductAbstractCollectionValidatorInterface
{
    /**
     * @param array<\Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractCollectionValidatorRuleInterface> $productAbstractCollectionValidatorRules
     * @param array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionUpdateValidatorPluginInterface> $productAbstractCollectionUpdateValidatorPlugins
     */
    public function __construct(
        protected readonly array $productAbstractCollectionValidatorRules,
        protected readonly array $productAbstractCollectionUpdateValidatorPlugins,
    ) {
    }

    public function validate(
        ProductAbstractCollectionRequestTransfer $productAbstractCollectionRequestTransfer
    ): ProductAbstractCollectionResponseTransfer {
        $productAbstractCollectionResponseTransfer = new ProductAbstractCollectionResponseTransfer();

        foreach ($productAbstractCollectionRequestTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $productAbstractCollectionResponseTransfer->addProductAbstract($productAbstractTransfer);
        }

        foreach ($this->productAbstractCollectionValidatorRules as $productAbstractCollectionValidatorRule) {
            $productAbstractCollectionResponseTransfer = $productAbstractCollectionValidatorRule->validate(
                $productAbstractCollectionRequestTransfer,
                $productAbstractCollectionResponseTransfer,
            );
        }

        foreach ($this->productAbstractCollectionUpdateValidatorPlugins as $productAbstractCollectionUpdateValidatorPlugin) {
            $productAbstractCollectionResponseTransfer = $productAbstractCollectionUpdateValidatorPlugin->validate(
                $productAbstractCollectionRequestTransfer,
                $productAbstractCollectionResponseTransfer,
            );
        }

        return $productAbstractCollectionResponseTransfer;
    }
}
