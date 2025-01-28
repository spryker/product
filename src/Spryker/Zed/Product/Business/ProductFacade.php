<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MessageSendingContextTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductExportCriteriaTransfer;
use Generated\Shared\Transfer\ProductPublisherConfigTransfer;
use Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer;
use Generated\Shared\Transfer\RawProductAttributesTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Product\Business\ProductBusinessFactory getFactory()
 * @method \Spryker\Zed\Product\Persistence\ProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\Product\Persistence\ProductEntityManagerInterface getEntityManager()
 */
class ProductFacade extends AbstractFacade implements ProductFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteCollection
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->addProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteCollection
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection)
    {
        return $this->getFactory()
            ->createProductManager()
            ->saveProduct($productAbstractTransfer, $productConcreteCollection);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->createProductAbstract($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->saveProductAbstract($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->hasProductAbstract($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->findProductAbstractIdBySku($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->findProductAbstractById($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($sku)
    {
        return $this->getFactory()
            ->createProductAbstractManager()
            ->getAbstractSkuFromProductConcrete($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($concreteSku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductAbstractIdByConcreteSku($concreteSku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->createProductConcrete($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->saveProductConcrete($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->hasProductConcrete($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku($sku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->findProductConcreteIdBySku($sku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function findProductConcretesBySkus(array $skus): array
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->findProductConcretesBySkus($skus);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idConcrete
     *
     * @return int|null
     */
    public function findProductAbstractIdByConcreteId(int $idConcrete): ?int
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->findProductAbstractIdByConcreteId($idConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProduct)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->findProductConcreteById($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductConcrete($concreteSku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Product\Business\ProductFacadeInterface::getRawProductConcreteTransfersByConcreteSkus()} instead.
     *
     * @param string $productConcreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getRawProductConcreteBySku(string $productConcreteSku): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getRawProductConcreteBySku($productConcreteSku);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getConcreteProductsByAbstractProductId($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasProductAttributeKey($key)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->hasAttributeKey($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer|null
     */
    public function findProductAttributeKey($key)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->findAttributeKey($key);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function createProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->createAttributeKey($productAttributeKeyTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function updateProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        return $this->getFactory()
            ->createAttributeKeyManager()
            ->updateAttributeKey($productAttributeKeyTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductAbstract($idProductAbstract)
    {
        $this->getFactory()
            ->createProductAbstractTouch()
            ->touchProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract)
    {
        $this->getFactory()
            ->createProductAbstractTouch()
            ->touchProductAbstractActive($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductInactive($idProductAbstract)
    {
        $this->getFactory()
            ->createProductAbstractTouch()
            ->touchProductAbstractInactive($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductDeleted($idProductAbstract)
    {
        $this->getFactory()
            ->createProductAbstractTouch()
            ->touchProductAbstractDeleted($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcrete($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteTouch()
            ->touchProductConcrete($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteActive($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteTouch()
            ->touchProductConcreteActive($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteInactive($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteTouch()
            ->touchProductConcreteInactive($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteDelete($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteTouch()
            ->touchProductConcreteDeleted($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->createProductUrl($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->updateProductUrl($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function updateProductsUrl(array $productAbstractTransfers): array
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->updateProductUrls($productAbstractTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductUrlManager()
            ->getProductUrl($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getFactory()
            ->createProductUrlManager()
            ->deleteProductUrl($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlActive(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getFactory()
            ->createProductUrlManager()
            ->touchProductAbstractUrlActive($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlDeleted(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->getFactory()
            ->createProductUrlManager()
            ->touchProductAbstractUrlDeleted($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductAbstractName(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractNameGenerator()
            ->getLocalizedProductAbstractName($productAbstractTransfer, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createProductConcreteNameGenerator()
            ->getLocalizedProductConcreteName($productConcreteTransfer, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteActivator()
            ->activateProductConcrete($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete)
    {
        $this->getFactory()
            ->createProductConcreteActivator()
            ->deactivateProductConcrete($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string> $productConcreteSkus
     *
     * @return void
     */
    public function deactivateProductConcretesByConcreteSkus(array $productConcreteSkus): void
    {
        $this->getFactory()
            ->createProductConcreteActivator()
            ->deactivateProductConcretesByConcreteSkus($productConcreteSkus);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $superAttributes
     * @param int $idProductConcrete
     *
     * @return array
     */
    public function generateAttributePermutations(array $superAttributes, $idProductConcrete)
    {
        return $this->getFactory()
            ->createAttributePermutationGenerator()
            ->generateAttributePermutations($superAttributes, $idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function generateVariants(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection)
    {
        return $this->getFactory()
            ->createProductVariantGenerator()
            ->generate($productAbstractTransfer, $attributeCollection);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductActive($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAbstractStatusChecker()
            ->isActive($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isProductConcreteActive(ProductConcreteTransfer $productConcreteTransfer): bool
    {
        return $this->getFactory()
            ->createProductConcreteStatusChecker()
            ->isActive($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return array
     */
    public function getCombinedAbstractAttributeKeys(ProductAbstractTransfer $productAbstractTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createAttributeLoader()
            ->getCombinedAbstractAttributeKeys($productAbstractTransfer, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productIds
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return array
     */
    public function getCombinedAbstractAttributeKeysForProductIds($productIds, ?LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createAttributeLoader()
            ->getCombinedAbstractAttributeKeysForProductIds($productIds, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    public function getCombinedConcreteAttributes(ProductConcreteTransfer $productConcreteTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        return $this->getFactory()
            ->createAttributeLoader()
            ->getCombinedConcreteAttributes($productConcreteTransfer, $localeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RawProductAttributesTransfer $rawProductAttributesTransfer
     *
     * @return array
     */
    public function combineRawProductAttributes(RawProductAttributesTransfer $rawProductAttributesTransfer)
    {
        return $this->getFactory()
            ->createAttributeMerger()
            ->merge($rawProductAttributesTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $attributes
     *
     * @return string
     */
    public function encodeProductAttributes(array $attributes)
    {
        return $this->getFactory()
            ->createAttributeEncoder()
            ->encodeAttributes($attributes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $attributes
     *
     * @return array
     */
    public function decodeProductAttributes($attributes)
    {
        return $this->getFactory()
            ->createAttributeEncoder()
            ->decodeAttributes($attributes);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteId(int $idProductConcrete): int
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductAbstractIdByConcreteId($idProductConcrete);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $suggestion
     *
     * @return array<string>
     */
    public function suggestProductAbstract(string $suggestion): array
    {
        return $this->getFactory()
            ->createProductSuggester()
            ->suggestProductAbstract($suggestion);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $suggestion
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractSuggestionCollectionTransfer
     */
    public function getPaginatedProductAbstractSuggestions(
        string $suggestion,
        PaginationTransfer $paginationTransfer
    ): ProductAbstractSuggestionCollectionTransfer {
        return $this->getFactory()
            ->createProductSuggester()
            ->getPaginatedProductAbstractSuggestions($suggestion, $paginationTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $suggestion
     *
     * @return array<string>
     */
    public function suggestProductConcrete(string $suggestion): array
    {
        return $this->getFactory()
            ->createProductSuggester()
            ->suggestProductConcrete($suggestion);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByAbstractProductId(int $idProductAbstract): array
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->findProductConcreteIdsByAbstractProductId($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductAbstractIdsByProductConcreteIds($productConcreteIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string> $skus
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByConcreteSkus(array $skus): array
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductConcreteIdsByConcreteSkus($skus);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return array
     */
    public function getProductConcreteSkusByConcreteIds(array $productIds): array
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductConcreteSkusByConcreteIds($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function generateProductConcreteSku(ProductAbstractTransfer $productAbstractTransfer, ProductConcreteTransfer $productConcreteTransfer): string
    {
        return $this->getFactory()
            ->createSkuGenerator()
            ->generateProductConcreteSku($productAbstractTransfer, $productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductIds(array $productIds): array
    {
        return $this->getRepository()
            ->getProductConcreteTransfersByProductIds($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->getRepository()
            ->getProductConcreteTransfersByProductAbstractIds($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getRawProductConcreteTransfersByConcreteSkus(array $productConcreteSkus): array
    {
        return $this->getFactory()
            ->createProductConcreteManager()
            ->getProductConcretesByConcreteSkus($productConcreteSkus);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByFilter(FilterTransfer $filterTransfer): array
    {
        return $this->getRepository()->getProductConcretesByFilter($filterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getRawProductConcreteTransfersByFilter(FilterTransfer $filterTransfer): array
    {
        return $this->getRepository()->getRawProductConcreteTransfersByFilter($filterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function getProductUrls(ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer): array
    {
        return $this->getRepository()->getProductUrls($productUrlCriteriaFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string> $productAbstractSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getRawProductAbstractTransfersByAbstractSkus(array $productAbstractSkus): array
    {
        return $this->getRepository()->getRawProductAbstractTransfersByAbstractSkus($productAbstractSkus);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductCriteriaTransfer $productCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByCriteria(ProductCriteriaTransfer $productCriteriaTransfer): array
    {
        return $this->getRepository()->getProductConcretesByCriteria($productCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return void
     */
    public function createProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        $this->getFactory()
            ->createProductConcreteWriter()
            ->createProductConcreteCollection($productConcreteCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<int, string>
     */
    public function getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract(array $productAbstractIds): array
    {
        return $this->getRepository()
            ->getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductPublisherEventNameMismatchException
     * @throws \Spryker\Zed\Product\Business\Exception\ProductPublisherWrongChunkSizeException
     *
     * @return void
     */
    public function emitPublishProductToMessageBroker(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void
    {
        $this->getFactory()->createProductMessageBrokerPublisher()->publish($productPublisherConfigTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageSendingContextTransfer $messageSendingContextTransfer
     *
     * @return bool
     */
    public function canPublishMessage(MessageSendingContextTransfer $messageSendingContextTransfer): bool
    {
        return $this->getFactory()->createProductMessageBrokerPublisher()
            ->canPublishMessage($messageSendingContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function publishProductToMessageBrokerByProductAbstractEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()->createProductMessageBrokerPublisher()->publishByProductAbstractEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function publishProductToMessageBrokerByProductEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()->createProductMessageBrokerPublisher()->publishByProductEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @return void
     */
    public function emitUnpublishProductToMessageBroker(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void
    {
        $this->getFactory()->createProductMessageBrokerPublisher()->unpublish($productPublisherConfigTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductExportCriteriaTransfer $productExportCriteriaTransfer
     *
     * @return void
     */
    public function triggerProductExportEvents(ProductExportCriteriaTransfer $productExportCriteriaTransfer): void
    {
        $this->getFactory()->createProductEventBusExporter()->export($productExportCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): ProductConcreteCollectionTransfer {
        return $this->getFactory()
            ->createProductConcreteReader()
            ->getProductConcreteCollection($productConcreteCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function getProductAbstractCollection(ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer): ProductAbstractCollectionTransfer
    {
        return $this->getFactory()->createProductAbstractReader()->getProductAbstractCollection($productAbstractCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer
     */
    public function getProductAttributeKeyCollection(
        ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
    ): ProductAttributeKeyCollectionTransfer {
        return $this->getRepository()->getProductAttributeKeyCollection($productAttributeKeyCriteriaTransfer);
    }
}
