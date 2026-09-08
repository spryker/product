<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Business\Product\LocalizedAttributesLocaleResolverInterface;
use Spryker\Zed\Product\Business\Product\Validator\ProductConcreteCollectionValidatorInterface;
use Spryker\Zed\Product\Persistence\ProductEntityManagerInterface;

class ProductConcreteCollectionUpdater implements ProductConcreteCollectionUpdaterInterface
{
    use TransactionTrait;

    /**
     * @param array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface> $beforeUpdatePlugins
     * @param array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface> $afterUpdatePlugins
     */
    public function __construct(
        protected readonly ProductConcreteCollectionValidatorInterface $productConcreteCollectionUpdateValidator,
        protected readonly ProductEntityManagerInterface $productEntityManager,
        protected readonly LocalizedAttributesLocaleResolverInterface $localizedAttributesLocaleResolver,
        protected readonly array $beforeUpdatePlugins,
        protected readonly array $afterUpdatePlugins,
    ) {
    }

    public function updateProductCollection(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer
    ): ProductConcreteCollectionResponseTransfer {
        $productConcreteCollectionResponseTransfer = $this->productConcreteCollectionUpdateValidator
            ->validate($productConcreteCollectionRequestTransfer);

        if ($productConcreteCollectionResponseTransfer->getErrors()->count() !== 0) {
            return $productConcreteCollectionResponseTransfer;
        }

        if ($productConcreteCollectionRequestTransfer->getIsTransactional() === false) {
            return $this->executeUpdateProductCollection($productConcreteCollectionResponseTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($productConcreteCollectionResponseTransfer): ProductConcreteCollectionResponseTransfer {
                return $this->executeUpdateProductCollection($productConcreteCollectionResponseTransfer);
            },
        );
    }

    protected function executeUpdateProductCollection(
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): ProductConcreteCollectionResponseTransfer {
        $productConcreteTransfers = $productConcreteCollectionResponseTransfer->getProducts();

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteTransfer->requireIdProductConcrete();

            $this->localizedAttributesLocaleResolver->resolveLocales(
                $productConcreteTransfer->getLocalizedAttributes(),
            );

            $this->executePlugins($this->beforeUpdatePlugins, $productConcreteTransfer);
        }

        $productConcreteCollectionTransfer = $this->createProductConcreteCollectionTransfer($productConcreteTransfers);

        $this->productEntityManager->updateProductConcreteCollection($productConcreteCollectionTransfer);
        $this->productEntityManager->updateProductConcreteCollectionLocalizedAttributes($productConcreteCollectionTransfer);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->executePlugins($this->afterUpdatePlugins, $productConcreteTransfer);
        }

        return $productConcreteCollectionResponseTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     */
    protected function createProductConcreteCollectionTransfer(ArrayObject $productConcreteTransfers): ProductConcreteCollectionTransfer
    {
        $productConcreteCollectionTransfer = new ProductConcreteCollectionTransfer();

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteCollectionTransfer->addProduct($productConcreteTransfer);
        }

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface> $plugins
     */
    protected function executePlugins(array $plugins, ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        foreach ($plugins as $plugin) {
            $productConcreteTransfer = $plugin->update($productConcreteTransfer);
        }

        return $productConcreteTransfer;
    }
}
