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

interface ProductFacadeInterface
{
    /**
     * Specification:
     * - Adds abstract product with its attributes, meta data, and concrete products.
     * - Throws exception if a concrete product with the same SKU exists.
     * - Throws exception if an abstract product with the same SKU exists.
     * - Triggers "before" and "after" CREATE plugins.
     * - Returns the ID of the newly created abstract product.
     * - Does not activate or touche created abstract and concrete products.
     * - Executes `ProductAbstractPreCreatePluginInterface` stack of plugins.
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
    public function addProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * Specification:
     * - Saves abstract product with its concrete products.
     * - Saves abstract product attributes.
     * - Saves abstract product meta data.
     * - Triggers "before" and "after" UPDATE plugins.
     * - Throws exception if a concrete product with the same SKU exists.
     * - Throws exception if an abstract product with the same SKU exists.
     * - Returns the ID of the abstract product.
     * - Does not activate or touche updated abstract and concrete products.
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
    public function saveProduct(ProductAbstractTransfer $productAbstractTransfer, array $productConcreteCollection);

    /**
     * Specification:
     * - Adds abstract product attributes.
     * - Adds abstract product localized attributes.
     * - Adds abstract product meta data.
     * - Triggers "before" and "after" CREATE plugins.
     * - Throws exception if an abstract product with the same SKU exists.
     * - Returns the ID of the newly created abstract product.
     * - Does not activate or touche created abstract product.
     * - Executes `ProductAbstractPreCreatePluginInterface` stack of plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return int
     */
    public function createProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Saves abstract product attributes.
     * - Saves abstract product localized attributes.
     * - Saves abstract product meta data.
     * - Updates the URL of an active abstract product if it is changed.
     * - Triggers "before" and "after" CREATE plugins.
     * - Throws exception if an abstract product with the same SKU exists.
     * - Does not activate or touche created abstract product.
     * - Returns the ID of the newly created abstract product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductAbstractExistsException
     *
     * @return int
     */
    public function saveProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Checks if the abstract product exists.
     *
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku);

    /**
     * Specification:
     * - Returns the ID of an abstract product for the given SKU if it exists, null otherwise.
     *
     * @api
     *
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku);

    /**
     * Specification:
     * - Returns an abstract product with attributes and localized attributes.
     * - Triggers READ plugins.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    public function findProductAbstractById($idProductAbstract);

    /**
     * Specification:
     * - Returns the SKU of an abstract product that belongs to the given SKU of a concrete product.
     * - Throws exception if no abstract product is found.
     *
     * @api
     *
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return string
     */
    public function getAbstractSkuFromProductConcrete($sku);

    /**
     * Specification:
     * - Returns the abstract product ID of the given concrete product SKU if it exists.
     * - Throws exception if no abstract product is found.
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($concreteSku);

    /**
     * Specification:
     * - Adds concrete product with attributes and localized attributes.
     * - Throws exception if a concrete product with the same SKU exists.
     * - Triggers "before" and "after" CREATE plugins.
     * - Returns the ID of the newly created concrete product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function createProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Saves concrete product with attributes and localized attributes.
     * - Throws exception if a concrete product with the same SKU exists.
     * - Triggers "before" and "after" UPDATE plugins.
     * - Returns the ID of the concrete product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteExistsException
     *
     * @return int
     */
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Checks if the concrete product exists.
     *
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku);

    /**
     * Specification:
     * - Returns the ID of the concrete product.
     *
     * @api
     *
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku($sku);

    /**
     * Specification:
     * - Returns concrete products transfers filtered by skus.
     *
     * @api
     *
     * @param array<string> $skus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function findProductConcretesBySkus(array $skus): array;

    /**
     * Specification:
     * - Returns the abstract product ID by given concrete product ID.
     *
     * @api
     *
     * @param int $idConcrete
     *
     * @return int|null
     */
    public function findProductAbstractIdByConcreteId(int $idConcrete): ?int;

    /**
     * Specification:
     * - Returns the concrete product with attributes and localized attributes.
     * - Returns null if the concrete product is not found by ID.
     * - Triggers READ plugins.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProduct);

    /**
     * Specification:
     * - Returns concrete product with attributes and localized attributes.
     * - Throws exception if the concrete product is not found by SKU.
     * - Triggers READ plugins.
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku);

    /**
     * Specification:
     * - Returns concrete product with attributes and localized attributes.
     * - Throws exception if the concrete product is not found.
     * - Triggers `ProductEvents::PRODUCT_CONCRETE_READ` event but doesn't trigger READ plugins.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Product\Business\ProductFacadeInterface::getRawProductConcreteTransfersByConcreteSkus()} instead.
     *
     * @param string $productConcreteSku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getRawProductConcreteBySku(string $productConcreteSku): ProductConcreteTransfer;

    /**
     * Specification:
     * - Returns concrete product collection.
     * - Triggers READ plugins.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getConcreteProductsByAbstractProductId($idProductAbstract);

    /**
     * Specification:
     * - Checks if the product attribute key exists.
     *
     * @api
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasProductAttributeKey($key);

    /**
     * Specification:
     * - Returns the product attribute key if it exists, null otherwise.
     *
     * @api
     *
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer|null
     */
    public function findProductAttributeKey($key);

    /**
     * Specification:
     * - Creates new product attribute key.
     * - Returns created product attribute key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function createProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer);

    /**
     * Specification:
     * - Updates an existing product attribute key.
     * - Returns the updated product attribute key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    public function updateProductAttributeKey(ProductAttributeKeyTransfer $productAttributeKeyTransfer);

    /**
     * Specification:
     * - Touches the abstract product and all of its concrete products.
     * - Touches related "product_abstract", "product_concrete", and "attribute_map" entries.
     * - Used touch event statuses (active, inactive) depend on the current status of the abstract product and its concrete products.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Touches "product_abstract" and "product_attribute_map" as active.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductActive($idProductAbstract);

    /**
     * Specification:
     * - Touches "product_abstract" and "product_attribute_map" as in-active.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductInactive($idProductAbstract);

    /**
     * Specification:
     * - Touches "product_abstract" and "product_attribute_map" as deleted.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function touchProductDeleted($idProductAbstract);

    /**
     * Specification:
     * - Touches a concrete product.
     * - Touches related "product_concrete", "product_abstract", and "attribute_map" entries.
     * - Used touch event statuses (active, inactive) depend on the current status of the concrete product.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcrete($idProductConcrete);

    /**
     * Specification:
     * - Touches "product_concrete" as active.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteActive($idProductConcrete);

    /**
     * Specification:
     * - Touches "product_concrete" as in-active.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteInactive($idProductConcrete);

    /**
     * Specification:
     * - Touches "product_concrete" as deleted.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function touchProductConcreteDelete($idProductConcrete);

    /**
     * Specification:
     * - Creates localized abstract product URLs based on abstract product localized attributes name.
     * - Executes touch logic for abstract product URL activation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Updates localized abstract product URLs based on abstract product localized attributes name.
     * - Executes touch logic for abstract product URL update.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Returns localized abstract product URLs for all available locales.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function getProductUrl(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Deletes all URLs belonging to the given abstract product.
     * - Executes touch logic for abstract product URL deletion.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function deleteProductUrl(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Touches the URL of the abstract product for all available locales as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlActive(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Touches the URL of the abstract product for all available locales as deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    public function touchProductAbstractUrlDeleted(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Returns localized abstract product name based on localized attributes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductAbstractName(ProductAbstractTransfer $productAbstractTransfer, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     * - Returns localized concrete product name based on localized attributes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getLocalizedProductConcreteName(ProductConcreteTransfer $productConcreteTransfer, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     * - Activates concrete product.
     * - Generates and saves the related abstract product URL.
     * - Touches concrete product as active.
     * - Touches abstract product URL as active.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function activateProductConcrete($idProductConcrete);

    /**
     * Specification:
     * - Deactivates concrete product.
     * - Touches concrete product as active.
     * - Deletes abstract product URL if abstract product is inactive.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function deactivateProductConcrete($idProductConcrete);

    /**
     * Specification:
     * - Deactivates concrete products.
     * - Touches concrete products as active.
     * - Deletes abstract product URL if abstract products are inactive.
     *
     * @api
     *
     * @param array<string> $productConcreteSkus
     *
     * @return void
     */
    public function deactivateProductConcretesByConcreteSkus(array $productConcreteSkus): void;

    /**
     * Specification:
     * - Generates all possible permutations of the given attributes.
     *
     * Leaf node of a tree is concrete id.
     * (
     *   [color:red] => array (
     *       [brand:nike] => array(
     *          [id] => 1
     *       )
     *   ),
     *   [brand:nike] => array(
     *       [color:red] => array(
     *          [id] => 1
     *       )
     *   )
     * )
     *
     * @api
     *
     * @param array $superAttributes
     * @param int $idProductConcrete
     *
     * @return array
     */
    public function generateAttributePermutations(array $superAttributes, $idProductConcrete);

    /**
     * Specification:
     * - Generates concrete products based on attributes.
     *
     * Expected input AttributeCollection structure:
     * (
     *     [color] => Array
     *      (
     *          [red] => Red
     *          [blue] => Blue
     *      )
     *     [flavor] => Array
     *      (
     *          [sweet] => Cakes
     *      )
     *     [size] => Array
     *      (
     *          [40] => 40
     *          [41] => 41
     *          [42] => 42
     *      )
     * )
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function generateVariants(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection);

    /**
     * Specification:
     * - Returns true if any of the concrete products of the abstract product is active.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductActive($idProductAbstract);

    /**
     * Specification:
     * - Returns true if concrete product is active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return bool
     */
    public function isProductConcreteActive(ProductConcreteTransfer $productConcreteTransfer): bool;

    /**
     * Specification:
     * - Returns the attribute keys of the abstract product and its concrete products.
     * - Includes localized abstract product and concrete products attribute keys when $localeTransfer is provided.
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
    public function getCombinedAbstractAttributeKeys(ProductAbstractTransfer $productAbstractTransfer, ?LocaleTransfer $localeTransfer = null);

    /**
     * Specification:
     * - Returns an array of productIds as keys with array of attribute keys as values of a persisted products.
     * - The attribute keys is a combination of the abstract product's attribute keys and all its existing concretes' attribute keys.
     * - If $localeTransfer is provided then localized abstract and concrete attribute keys are also part of the result.
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
    public function getCombinedAbstractAttributeKeysForProductIds($productIds, ?LocaleTransfer $localeTransfer = null);

    /**
     * Specification:
     * - Returns an associative array of attribute key - attribute value pairs of the persisted concrete product.
     * - The result is a combination of the concrete product's attributes and its abstract product's attributes.
     * - Includes localized abstract product and concrete products attribute keys when $localeTransfer is provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array
     */
    public function getCombinedConcreteAttributes(ProductConcreteTransfer $productConcreteTransfer, ?LocaleTransfer $localeTransfer = null);

    /**
     * Specification:
     * - Returns an associative array of attribute key - attribute value pairs.
     * - The result is the correct inheritance combination of the provided raw product attribute data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RawProductAttributesTransfer $rawProductAttributesTransfer
     *
     * @return array
     */
    public function combineRawProductAttributes(RawProductAttributesTransfer $rawProductAttributesTransfer);

    /**
     * Specification:
     * - Encodes an array of product attribute key - attribute value pairs to JSON string.
     *
     * @api
     *
     * @param array $attributes
     *
     * @return string
     */
    public function encodeProductAttributes(array $attributes);

    /**
     * Specification:
     * - Decodes product attributes JSON string to an array of attribute key - attribute value pairs.
     *
     * @api
     *
     * @param string $attributes
     *
     * @return array
     */
    public function decodeProductAttributes($attributes);

    /**
     * Specification:
     * - Suggests product abstract by name or SKU.
     *
     * @api
     *
     * @param string $suggestion
     *
     * @return array<string>
     */
    public function suggestProductAbstract(string $suggestion): array;

    /**
     * Specification:
     * - Suggests product abstract transfers by name or SKU.
     * - Uses pagination for returning suggestions.
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
    ): ProductAbstractSuggestionCollectionTransfer;

    /**
     * Specification:
     * - Suggests product concrete by name or SKU.
     *
     * @api
     *
     * @param string $suggestion
     *
     * @return array<string>
     */
    public function suggestProductConcrete(string $suggestion): array;

    /**
     * Specification:
     * - Finds product concrete ids by product abstract id.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function findProductConcreteIdsByAbstractProductId(int $idProductAbstract): array;

    /**
     * Specification:
     * - Returns product concrete ids by each product abstract id.
     * - Keys are product concrete ids. Values are product abstract ids.
     *
     * @api
     *
     * @param array<int> $productConcreteIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByProductConcreteIds(array $productConcreteIds): array;

    /**
     * Specification:
     * - Returns the abstract product ID of the given concrete product ID if it exists.
     * - Throws exception if no abstract product is found.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteId(int $idProductConcrete): int;

    /**
     * Specification:
     * - Finds product concrete ids by concrete skus.
     *
     * Expected result structure:
     * [
     *     'sku' => 'id_product_concrete',
     *     ...
     * ]
     *
     * @api
     *
     * @param array<string> $skus
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByConcreteSkus(array $skus): array;

    /**
     * Specification:
     * - Finds product concrete ids by concrete skus.
     *
     * Expected result structure:
     * [
     *     'sku' => 'id_product_concrete',
     *     ...
     * ]
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return array
     */
    public function getProductConcreteSkusByConcreteIds(array $productIds): array;

    /**
     * Specification:
     * - Returns the generated SKU for new concrete product that build from the given attributes or incremented value within the abstract product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function generateProductConcreteSku(ProductAbstractTransfer $productAbstractTransfer, ProductConcreteTransfer $productConcreteTransfer): string;

    /**
     * Specification:
     * - Returns concrete product transfers array by their product ids.
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductIds(array $productIds): array;

    /**
     * Specification:
     * - Returns concrete product transfers by product abstract ids.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersByProductAbstractIds(array $productAbstractIds): array;

    /**
     * Specification:
     * - Returns concrete products w/o joined data.
     *
     * @api
     *
     * @param array $productConcreteSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getRawProductConcreteTransfersByConcreteSkus(array $productConcreteSkus): array;

    /**
     * Specification:
     * - Retrieves product concrete transfers according to given filter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByFilter(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Retrieves product concrete transfers according to given filter.
     * - Maps only data from `spy_product` table without any joined data for performance reasons.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getRawProductConcreteTransfersByFilter(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Retrieves url records from Persistence.
     * - Filters by criteria from ProductUrlCriteriaFilterTransfer.
     * - Returns array of UrlTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function getProductUrls(ProductUrlCriteriaFilterTransfer $productUrlCriteriaFilterTransfer): array;

    /**
     * Specification:
     * - Retrieves product abstract transfer by sku.
     * - Doesn't populate it with additional data.
     *
     * @api
     *
     * @param array<string> $productAbstractSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractTransfer>
     */
    public function getRawProductAbstractTransfersByAbstractSkus(array $productAbstractSkus): array;

    /**
     * Specification:
     * - Retrieves product concrete transfers according to given criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductCriteriaTransfer $productCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcretesByCriteria(ProductCriteriaTransfer $productCriteriaTransfer): array;

    /**
     * Specification:
     * - Creates new concrete products and corresponding localized attributes.
     * - Requires ProductConcrete.sku and ProductConcrete.idProductAbstract transfer properties to be set.
     * - Throws exception if a concrete product with the same SKU exists.
     * - Triggers "before" and "after" CREATE plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return void
     */
    public function createProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void;

    /**
     * Specification:
     * - Retrieves non-empty localized attribute names from persistence by provided product abstract ids.
     * - Sets first found localized attribute name per each abstract product.
     * - Returns localized attribute names indexed by product abstract id.
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     *
     * @return array<int, string>
     */
    public function getProductAbstractLocalizedAttributeNamesIndexedByIdProductAbstract(array $productAbstractIds): array;

    /**
     * Specification:
     * - Retrieves ProductConcrete entities from the DB by the provided IDs.
     * - Requires ProductPublisherConfigTransfer.productIds.
     * - Requires ProductPublisherConfigTransfer.eventName.
     * - Throws ProductPublisherEventNameMismatchException if ProductPublisherConfigTransfer.eventName contents unsupported event name.
     * - Sends ProductPublisherConfigTransfer.eventName event to the event bus.
     * - Uses the tenant identifier, if set, as a store reference to be used when sending the message.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductPublisherEventNameMismatchException
     *
     * @return void
     */
    public function emitPublishProductToMessageBroker(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void;

    /**
     * Specification:
     * - Validates whether `ProductConfig::isPublishingToMessageBrokerEnabled()` is `true`; returns `false` otherwise.
     * - Returns `true` if the message is eligible for publishing via the message broker.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MessageSendingContextTransfer $messageSendingContextTransfer
     *
     * @return bool
     */
    public function canPublishMessage(MessageSendingContextTransfer $messageSendingContextTransfer): bool;

    /**
     * Specification:
     * - Uses EventEntityTransfer.id, EventEntityTransfer.name and EventEntityTransfer.foreignKeys to collect product abstract IDs.
     * - When an event is not product event, but event of related entity, new event `Product.product_abstract.update` is triggered with the product abstract ID.
     * - When an event is a product event, emits `ProductUpdated` messages to Message Broker, read {@link emitPublishProductToMessageBroker()} specification for details.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function publishProductToMessageBrokerByProductAbstractEvents(array $eventEntityTransfers): void;

    /**
     *  Specification:
     *  - Uses EventEntityTransfer.id, EventEntityTransfer.name and EventEntityTransfer.foreignKeys to collect product IDs.
     *  - When an event is not product event, but event of related entity, new event `Product.product_concrete.update` is triggered with the product ID.
     *  - When an event is a product event, emits `ProductUpdated` messages to Message Broker, read {@link emitPublishProductToMessageBroker()} specification for details.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function publishProductToMessageBrokerByProductEvents(array $eventEntityTransfers): void;

    /**
     * Specification:
     * - Requires ProductPublisherConfigTransfer.productIds.
     * - Sends ProductDeletedTransfer event to the event bus.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPublisherConfigTransfer $productPublisherConfigTransfer
     *
     * @return void
     */
    public function emitUnpublishProductToMessageBroker(ProductPublisherConfigTransfer $productPublisherConfigTransfer): void;

    /**
     * Specification:
     * - Checks if productExportCriteria.storeReference is modified.
     * - Filters concrete products by store when provided in criteria.
     * - Triggers Product.product_concrete.export event for concrete products filtered by the criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductExportCriteriaTransfer $productExportCriteriaTransfer
     *
     * @return void
     */
    public function triggerProductExportEvents(ProductExportCriteriaTransfer $productExportCriteriaTransfer): void;

    /**
     * Specification:
     * - Retrieves product concrete entities filtered by criteria.
     * - Uses `ProductConcreteCriteriaTransfer.productConcreteConditions.skus` to filter products by `skus`.
     * - Uses `ProductConcreteCriteriaTransfer.productConcreteConditions.localeNames` to filter products by `localeNames`.
     * - Uses `ProductConcreteCriteriaTransfer.SortTransfer.field` to set the `order by` field.
     * - Uses `ProductConcreteCriteriaTransfer.SortTransfer.isAscending` to set ascending order otherwise will be used descending order.
     * - Uses `ProductConcreteCriteriaTransfer.PaginationTransfer.{limit, offset}` to paginate result with limit and offset.
     * - Uses `ProductConcreteCriteriaTransfer.PaginationTransfer.{page, maxPerPage}` to paginate result with page and maxPerPage.
     * - Executes the stack of {@link \Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface} plugins.
     * - Returns `ProductConcreteCollectionTransfer` filled with found products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): ProductConcreteCollectionTransfer;

    /**
     * Specification:
     * - Reads product abstract collection from Persistence.
     * - Filters by `ProductAbstractCriteria.productAbstractConditions`.
     * - Orders product abstract collection by `ProductAbstractCriteria.sortCollection`.
     * - Paginates product abstract collection.
     * - Returns product abstract entities data mapped to business transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function getProductAbstractCollection(ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer): ProductAbstractCollectionTransfer;

    /**
     * Specification:
     * - Retrieves product attribute key entities filtered by criteria.
     * - Uses `ProductAttributeKeyCriteriaTransfer.productAttributeKeyConditions.keys` to filter by `key`.
     * - Uses `ProductAttributeKeyCriteriaTransfer.productAttributeKeyConditions.isSuper` to filter by `isSuper` flag.
     * - Uses `ProductAttributeKeyCriteriaTransfer.sortCollection.field` to set the `order by` field.
     * - Uses `ProductAttributeKeyCriteriaTransfer.sortCollection.isAscending` to set ascending order otherwise will be used descending order.
     * - Uses `ProductAttributeKeyCriteriaTransfer.pagination.{limit, offset}` to paginate result with limit and offset.
     * - Uses `ProductAttributeKeyCriteriaTransfer.pagination.{page, maxPerPage}` to paginate result with page and maxPerPage.
     * - Returns `ProductAttributeKeyCollectionTransfer` filled with found product attribute keys.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyCollectionTransfer
     */
    public function getProductAttributeKeyCollection(
        ProductAttributeKeyCriteriaTransfer $productAttributeKeyCriteriaTransfer
    ): ProductAttributeKeyCollectionTransfer;
}
