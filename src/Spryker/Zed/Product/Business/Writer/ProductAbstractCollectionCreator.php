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

class ProductAbstractCollectionCreator implements ProductAbstractCollectionCreatorInterface
{
    use TransactionTrait;

    public function __construct(
        protected readonly ProductAbstractCollectionValidatorInterface $productAbstractCollectionCreateValidator,
        protected readonly ProductAbstractManagerInterface $productAbstractManager,
    ) {
    }

    public function createProductAbstractCollection(
        ProductAbstractCollectionRequestTransfer $productAbstractCollectionRequestTransfer
    ): ProductAbstractCollectionResponseTransfer {
        $productAbstractCollectionResponseTransfer = $this->productAbstractCollectionCreateValidator
            ->validate($productAbstractCollectionRequestTransfer);

        if ($productAbstractCollectionResponseTransfer->getErrors()->count() !== 0) {
            return $productAbstractCollectionResponseTransfer;
        }

        if ($productAbstractCollectionRequestTransfer->getIsTransactional() === false) {
            return $this->executeCreateProductAbstractCollection($productAbstractCollectionResponseTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($productAbstractCollectionResponseTransfer): ProductAbstractCollectionResponseTransfer {
                return $this->executeCreateProductAbstractCollection($productAbstractCollectionResponseTransfer);
            },
        );
    }

    protected function executeCreateProductAbstractCollection(
        ProductAbstractCollectionResponseTransfer $productAbstractCollectionResponseTransfer
    ): ProductAbstractCollectionResponseTransfer {
        foreach ($productAbstractCollectionResponseTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $productAbstractTransfer->setIdProductAbstract(
                $this->productAbstractManager->createProductAbstract($productAbstractTransfer),
            );
        }

        return $productAbstractCollectionResponseTransfer;
    }
}
