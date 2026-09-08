<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Writer;

use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface;
use Spryker\Zed\Product\Business\Product\LocalizedAttributesLocaleResolverInterface;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\Persistence\ProductEntityManagerInterface;

class ProductConcreteWriter implements ProductConcreteWriterInterface
{
    use TransactionTrait;

    protected ProductConcreteManagerInterface $productConcreteManager;

    protected ProductEntityManagerInterface $productEntityManager;

    protected ProductConcreteAssertionInterface $productConcreteAssertion;

    protected LocalizedAttributesLocaleResolverInterface $localizedAttributesLocaleResolver;

    public function __construct(
        ProductConcreteManagerInterface $productConcreteManager,
        ProductEntityManagerInterface $productEntityManager,
        ProductConcreteAssertionInterface $productConcreteAssertion,
        LocalizedAttributesLocaleResolverInterface $localizedAttributesLocaleResolver,
    ) {
        $this->productConcreteManager = $productConcreteManager;
        $this->productEntityManager = $productEntityManager;
        $this->productConcreteAssertion = $productConcreteAssertion;
        $this->localizedAttributesLocaleResolver = $localizedAttributesLocaleResolver;
    }

    public function createProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteCollectionTransfer): void {
            $this->executeCreateProductConcreteCollectionTransaction($productConcreteCollectionTransfer);
        });
    }

    protected function executeCreateProductConcreteCollectionTransaction(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $this->productConcreteAssertion->assertSkuIsUnique($productConcreteTransfer->getSku());

            $this->localizedAttributesLocaleResolver->resolveLocales(
                $productConcreteTransfer->getLocalizedAttributes(),
            );

            $this->productConcreteManager->notifyBeforeCreateObservers($productConcreteTransfer);
        }

        $productConcreteCollectionTransfer = $this->productEntityManager
            ->createProductConcreteCollection($productConcreteCollectionTransfer);
        $this->productEntityManager->createProductConcreteCollectionLocalizedAttributes($productConcreteCollectionTransfer);

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $this->productConcreteManager->notifyAfterCreateObservers($productConcreteTransfer);
        }
    }
}
