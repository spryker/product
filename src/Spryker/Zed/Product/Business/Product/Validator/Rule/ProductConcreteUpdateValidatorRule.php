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
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductConcreteUpdateValidatorRule extends AbstractProductValidatorRule implements ProductConcreteCollectionValidatorRuleInterface
{
    public function __construct(
        protected readonly ProductRepositoryInterface $productRepository,
        protected readonly ProductConcreteManagerInterface $productConcreteManager,
    ) {
    }

    public function validate(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer,
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): ProductConcreteCollectionResponseTransfer {
        $persistedProductEntityTransfers = $this->getPersistedProductConcreteEntityTransfersIndexedByIdProductConcrete(
            $productConcreteCollectionRequestTransfer,
        );

        foreach ($productConcreteCollectionRequestTransfer->getProducts() as $productConcreteTransfer) {
            $this->validateProductConcrete(
                $productConcreteTransfer,
                $persistedProductEntityTransfers,
                $productConcreteCollectionResponseTransfer,
            );
        }

        return $productConcreteCollectionResponseTransfer;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\SpyProductEntityTransfer> $persistedProductEntityTransfers
     */
    protected function validateProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        array $persistedProductEntityTransfers,
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): void {
        $sku = $productConcreteTransfer->getSku();

        $idProductConcrete = $productConcreteTransfer->getIdProductConcrete();

        if ($idProductConcrete === null) {
            $productConcreteCollectionResponseTransfer->addError(
                $this->createError($sku, 'Field "idProductConcrete" is required to update a concrete product.'),
            );

            return;
        }

        if (!isset($persistedProductEntityTransfers[$idProductConcrete])) {
            $productConcreteCollectionResponseTransfer->addError(
                $this->createError($sku, sprintf('Concrete product with ID "%d" does not exist.', $idProductConcrete)),
            );

            return;
        }

        $persistedProductEntityTransfer = $persistedProductEntityTransfers[$idProductConcrete];

        $this->validateSkuIsNotTaken($sku, $idProductConcrete, $productConcreteCollectionResponseTransfer);
        $this->validateAbstractSkuIsUnchanged(
            $productConcreteTransfer,
            $persistedProductEntityTransfer,
            $productConcreteCollectionResponseTransfer,
        );

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

    protected function validateSkuIsNotTaken(
        ?string $sku,
        int $idProductConcrete,
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): void {
        if ($sku === null) {
            return;
        }

        $idProductConcreteOwningSku = $this->productConcreteManager->findProductConcreteIdBySku($sku);

        if ($idProductConcreteOwningSku === null || $idProductConcreteOwningSku === $idProductConcrete) {
            return;
        }

        $productConcreteCollectionResponseTransfer->addError(
            $this->createError($sku, sprintf('Concrete product with SKU "%s" already exists.', $sku)),
        );
    }

    protected function validateAbstractSkuIsUnchanged(
        ProductConcreteTransfer $productConcreteTransfer,
        SpyProductEntityTransfer $persistedProductEntityTransfer,
        ProductConcreteCollectionResponseTransfer $productConcreteCollectionResponseTransfer
    ): void {
        $requestedAbstractSku = $productConcreteTransfer->getAbstractSku();
        $persistedAbstractSku = $persistedProductEntityTransfer->getSpyProductAbstract()?->getSku();

        if ($requestedAbstractSku === null || $persistedAbstractSku === null) {
            return;
        }

        if ($requestedAbstractSku === $persistedAbstractSku) {
            return;
        }

        $productConcreteCollectionResponseTransfer->addError(
            $this->createError(
                $productConcreteTransfer->getSku(),
                sprintf('Field "abstractSku" is immutable and cannot be changed to "%s".', $requestedAbstractSku),
            ),
        );
    }

    /**
     * @return array<int, \Generated\Shared\Transfer\SpyProductEntityTransfer>
     */
    protected function getPersistedProductConcreteEntityTransfersIndexedByIdProductConcrete(
        ProductConcreteCollectionRequestTransfer $productConcreteCollectionRequestTransfer
    ): array {
        $productIds = [];

        foreach ($productConcreteCollectionRequestTransfer->getProducts() as $productConcreteTransfer) {
            $idProductConcrete = $productConcreteTransfer->getIdProductConcrete();

            if ($idProductConcrete !== null) {
                $productIds[] = $idProductConcrete;
            }
        }

        if ($productIds === []) {
            return [];
        }

        $productEntityTransfersByIdProductConcrete = [];

        foreach ($this->productRepository->findProductConcreteByIds(array_values(array_unique($productIds))) as $productEntityTransfer) {
            $productEntityTransfersByIdProductConcrete[$productEntityTransfer->getIdProductOrFail()] = $productEntityTransfer;
        }

        return $productEntityTransfersByIdProductConcrete;
    }
}
