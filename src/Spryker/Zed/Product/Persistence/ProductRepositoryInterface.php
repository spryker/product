<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;

interface ProductRepositoryInterface
{
    /**
     * @param string $productConcreteSku
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer|null
     */
    public function findProductConcreteBySku(string $productConcreteSku): ?SpyProductEntityTransfer;

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer|null
     */
    public function findProductConcreteById(int $idProductConcrete): ?SpyProductEntityTransfer;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductEntityTransfer>
     */
    public function findProductConcreteByIds(array $productIds);

    /**
     * @param string $search
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $limit
     *
     * @return array
     */
    public function findProductAbstractDataBySkuOrLocalizedName(string $search, LocaleTransfer $localeTransfer, int $limit): array;

    /**
     * @param string $search
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $limit
     *
     * @return array
     */
    public function findProductConcreteDataBySkuOrLocalizedName(string $search, LocaleTransfer $localeTransfer, int $limit): array;

    /**
     * @param int $idProductConcrete
     *
     * @return int|null
     */
    public function findProductAbstractIdByConcreteId(int $idProductConcrete): ?int;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByAbstractProductId(int $idProductAbstract): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isProductConcreteActive(ProductConcreteTransfer $productConcreteTransfer): bool;

    /**
     * @param array<string> $skus
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByConcreteSkus(array $skus): array;

    /**
     * @param array<int> $productIds
     *
     * @return array
     */
    public function getProductConcreteSkusByConcreteIds(array $productIds): array;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductIds(array $productIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param string $search
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer
     */
    public function getProductAbstractSuggestionCollectionBySkuOrLocalizedName(
        string $search,
        PaginationTransfer $paginationTransfer,
        LocaleTransfer $localeTransfer
    ): ProductAbstractSuggestionCollectionTransfer;

    /**
     * @param array<string> $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByConcreteSkus(array $productConcreteSkus): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByFilter(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getRawProductConcreteTransfersByFilter(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function getProductUrls(ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer): array;

    /**
     * @param array<string> $productAbstractSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getRawProductAbstractTransfersByAbstractSkus(array $productAbstractSkus): array;

    /**
     * @param \Generated\Shared\Transfer\ProductCriteriaTransfer $productCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByCriteria(ProductCriteriaTransfer $productCriteriaTransfer): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getActiveProductAbstractsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getRawProductAbstractsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<int, string>
     */
    public function getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract(array $productAbstractIds): array;

    /**
     * Result format:
     * [
     *     $idProduct => [LocalizedAttributesTransfer, ...],
     *     ...
     * ]
     *
     * @param array<int> $productIds
     *
     * @return array<int, array<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>>
     */
    public function getLocalizedAttributesGroupedByIdProduct(array $productIds): array;

    /**
     * @param int $productExportPublishChunkSize
     * @param int $lastProductId
     * @param int|null $idStore Deprecated: Will be removed without replacement.
     *
     * @return array<int>
     */
    public function getAllProductConcreteIdsWithLimit(
        int $productExportPublishChunkSize,
        int $lastProductId,
        ?int $idStore = null
    ): array;

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function getProductAbstractCollection(ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer): ProductAbstractCollectionTransfer;

    /**
     * @param array<int, int> $productAbstractIds
     *
     * @return array<int, \Generated\Shared\Transfer\StoreRelationTransfer>
     */
    public function getProductAbstractStoreRelations(array $productAbstractIds): array;

    /**
     * @param array<int, int> $productAbstractIds
     *
     * @return array<int, array<\Generated\Shared\Transfer\LocalizedAttributesTransfer>>
     */
    public function getProductAbstractLocalizedAttributes(array $productAbstractIds): array;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): ProductConcreteCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer
     */
    public function getProductAttributeKeyCollection(
        ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
    ): ProductAttributeKeyCollectionTransfer;

    /**
     * @param array<int> $productAbstractIds
     * @param callable $indexGenerator
     *
     * @return array<string, \Generated\Shared\Transfer\UrlTransfer>
     */
    public function getUrlsByProductAbstractIds(array $productAbstractIds, callable $indexGenerator): array;
}
