<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Writer;

use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface;
use Spryker\Zed\Product\Business\Product\Validator\ProductAbstractCollectionValidatorInterface;

class ProductAbstractCollectionUpdater implements ProductAbstractCollectionUpdaterInterface
{
    use TransactionTrait;

    public function __construct(
        protected readonly ProductAbstractCollectionValidatorInterface $productAbstractCollectionUpdateValidator,
        protected readonly ProductAbstractManagerInterface $productAbstractManager,
    ) {
    }

    public function updateProductAbstractCollection(
        ProductAbstractCollectionRequestTransfer $productAbstractCollectionRequestTransfer
    ): ProductAbstractCollectionResponseTransfer {
        $productAbstractCollectionResponseTransfer = $this->productAbstractCollectionUpdateValidator
            ->validate($productAbstractCollectionRequestTransfer);

        if ($productAbstractCollectionResponseTransfer->getErrors()->count() !== 0) {
            return $productAbstractCollectionResponseTransfer;
        }

        if ($productAbstractCollectionRequestTransfer->getIsTransactional() === false) {
            return $this->executeUpdateProductAbstractCollection($productAbstractCollectionResponseTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($productAbstractCollectionResponseTransfer): ProductAbstractCollectionResponseTransfer {
                return $this->executeUpdateProductAbstractCollection($productAbstractCollectionResponseTransfer);
            },
        );
    }

    protected function executeUpdateProductAbstractCollection(
        ProductAbstractCollectionResponseTransfer $productAbstractCollectionResponseTransfer
    ): ProductAbstractCollectionResponseTransfer {
        foreach ($productAbstractCollectionResponseTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $productAbstractTransfer->setIdProductAbstract(
                $this->productAbstractManager->saveProductAbstract($productAbstractTransfer),
            );
        }

        return $productAbstractCollectionResponseTransfer;
    }
}
