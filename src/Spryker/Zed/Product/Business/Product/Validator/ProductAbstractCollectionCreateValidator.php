<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Product\Validator;

use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionResponseTransfer;

class ProductAbstractCollectionCreateValidator implements ProductAbstractCollectionValidatorInterface
{
    /**
     * @param array<\Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractCollectionValidatorRuleInterface> $productAbstractCollectionValidatorRules
     * @param array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionCreateValidatorPluginInterface> $productAbstractCollectionCreateValidatorPlugins
     */
    public function __construct(
        protected readonly array $productAbstractCollectionValidatorRules,
        protected readonly array $productAbstractCollectionCreateValidatorPlugins,
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

        foreach ($this->productAbstractCollectionCreateValidatorPlugins as $productAbstractCollectionCreateValidatorPlugin) {
            $productAbstractCollectionResponseTransfer = $productAbstractCollectionCreateValidatorPlugin->validate(
                $productAbstractCollectionRequestTransfer,
                $productAbstractCollectionResponseTransfer,
            );
        }

        return $productAbstractCollectionResponseTransfer;
    }
}
