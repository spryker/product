<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Writer;

use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\Business\Product\Validator\ProductConcreteCollectionValidatorInterface;

class ProductConcreteCollectionCreator implements ProductConcreteCollectionCreatorInterface
{
    use TransactionTrait;

    public function __construct(
        protected readonly ProductConcreteCollectionValidatorInterface $productConcreteCollectionCreateValidator,
        protected readonly ProductConcreteManagerInterface $productConcreteManager,
    ) {
    }

    public function createProductCollection(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer
    ): ProductConcreteCollectionResponseTransfer {
        $productConcreteCollectionResponseTransfer = $this->productConcreteCollectionCreateValidator
            ->validate($productConcreteCollectionRequestTransfer);

        if ($productConcreteCollectionResponseTransfer->getErrors()->count() !== 0) {
            return $productConcreteCollectionResponseTransfer;
        }

        if ($productConcreteCollectionRequestTransfer->getIsTransactional() === false) {
            return $this->executeCreateProductCollection($productConcreteCollectionResponseTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($productConcreteCollectionResponseTransfer): ProductConcreteCollectionResponseTransfer {
                return $this->executeCreateProductCollection($productConcreteCollectionResponseTransfer);
            },
        );
    }

    protected function executeCreateProductCollection(
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): ProductConcreteCollectionResponseTransfer {
        foreach ($productConcreteCollectionResponseTransfer->getProducts() as $productConcreteTransfer) {
            $productConcreteTransfer->setIdProductConcrete(
                $this->productConcreteManager->createProductConcrete($productConcreteTransfer),
            );
        }

        return $productConcreteCollectionResponseTransfer;
    }
}
