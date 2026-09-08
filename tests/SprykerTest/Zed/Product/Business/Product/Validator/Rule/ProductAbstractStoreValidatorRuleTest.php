<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Validator\Rule;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractStoreValidatorRule;
use Spryker\Zed\Product\Business\Reader\StoreReaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Product
 * @group Validator
 * @group Rule
 * @group ProductAbstractStoreValidatorRuleTest
 *
 * Add your own group annotations below this line
 */
class ProductAbstractStoreValidatorRuleTest extends Unit
{
    protected const string KNOWN_STORE_NAME = 'DE';

    protected const string UNKNOWN_STORE_NAME = 'ZZ-non-existent-store';

    protected const string PRODUCT_ABSTRACT_SKU = 'abstract-sku';

    protected const string OTHER_PRODUCT_ABSTRACT_SKU = 'other-abstract-sku';

    protected const string ERROR_MESSAGE_PATTERN = 'Store "%s" does not exist.';

    public function testValidateReturnsNoErrorsWhenStoreNameExists(): void
    {
        // Arrange
        $storeReaderMock = $this->createStoreReaderMock([]);
        $request = $this->createRequest([static::PRODUCT_ABSTRACT_SKU => [static::KNOWN_STORE_NAME]]);

        // Act
        $response = (new ProductAbstractStoreValidatorRule($storeReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $response->getErrors());
    }

    public function testValidateReturnsErrorWithMessageForUnknownStoreName(): void
    {
        // Arrange
        $storeReaderMock = $this->createStoreReaderMock([static::UNKNOWN_STORE_NAME]);
        $request = $this->createRequest([static::PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_STORE_NAME]]);

        // Act
        $response = (new ProductAbstractStoreValidatorRule($storeReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $errorTransfer = $response->getErrors()->offsetGet(0);
        $this->assertSame(static::PRODUCT_ABSTRACT_SKU, $errorTransfer->getEntityIdentifier());
        $this->assertSame(sprintf(static::ERROR_MESSAGE_PATTERN, static::UNKNOWN_STORE_NAME), $errorTransfer->getMessage());
    }

    public function testValidateReturnsErrorsOnlyForUnknownStoreNames(): void
    {
        // Arrange
        $storeReaderMock = $this->createStoreReaderMock([static::UNKNOWN_STORE_NAME]);
        $request = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::KNOWN_STORE_NAME, static::UNKNOWN_STORE_NAME],
        ]);

        // Act
        $response = (new ProductAbstractStoreValidatorRule($storeReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $this->assertSame(
            sprintf(static::ERROR_MESSAGE_PATTERN, static::UNKNOWN_STORE_NAME),
            $response->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testValidateReturnsErrorPerProductAbstractForSameUnknownStoreName(): void
    {
        // Arrange
        $storeReaderMock = $this->createStoreReaderMock([static::UNKNOWN_STORE_NAME]);
        $request = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_STORE_NAME],
            static::OTHER_PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_STORE_NAME],
        ]);

        // Act
        $response = (new ProductAbstractStoreValidatorRule($storeReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(2, $response->getErrors());
        $entityIdentifiers = [];

        foreach ($response->getErrors() as $errorTransfer) {
            $entityIdentifiers[] = $errorTransfer->getEntityIdentifier();
        }

        $this->assertEqualsCanonicalizing(
            [static::PRODUCT_ABSTRACT_SKU, static::OTHER_PRODUCT_ABSTRACT_SKU],
            $entityIdentifiers,
        );
    }

    public function testValidateReturnsSingleErrorForDuplicatedUnknownStoreName(): void
    {
        // Arrange
        $storeReaderMock = $this->createStoreReaderMock([static::UNKNOWN_STORE_NAME]);
        $request = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_STORE_NAME, static::UNKNOWN_STORE_NAME],
        ]);

        // Act
        $response = (new ProductAbstractStoreValidatorRule($storeReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
    }

    public function testValidateResolvesUnknownStoreNamesOnceForTheWholeCollection(): void
    {
        // Arrange
        $storeReaderMock = $this->createMock(StoreReaderInterface::class);
        $storeReaderMock
            ->expects($this->once())
            ->method('getMissingStoreNames')
            ->with($this->equalTo([static::KNOWN_STORE_NAME, static::UNKNOWN_STORE_NAME]))
            ->willReturn([]);

        $request = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::KNOWN_STORE_NAME],
            static::OTHER_PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_STORE_NAME],
        ]);

        // Act
        (new ProductAbstractStoreValidatorRule($storeReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());
    }

    public function testValidatePassesEmptyStoreNamesWhenNoStoreRelationProvided(): void
    {
        // Arrange
        $storeReaderMock = $this->createMock(StoreReaderInterface::class);
        $storeReaderMock
            ->expects($this->once())
            ->method('getMissingStoreNames')
            ->with($this->equalTo([]))
            ->willReturn([]);

        $request = (new ProductAbstractCollectionRequestTransfer())
            ->addProductAbstract((new ProductAbstractTransfer())->setSku(static::PRODUCT_ABSTRACT_SKU));

        // Act
        $response = (new ProductAbstractStoreValidatorRule($storeReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $response->getErrors());
    }

    public function testValidateIgnoresStoresWithoutName(): void
    {
        // Arrange
        $storeReaderMock = $this->createStoreReaderMock([]);
        $request = (new ProductAbstractCollectionRequestTransfer())
            ->addProductAbstract(
                (new ProductAbstractTransfer())
                    ->setSku(static::PRODUCT_ABSTRACT_SKU)
                    ->setStoreRelation((new StoreRelationTransfer())->addStores(new StoreTransfer())),
            );

        // Act
        $response = (new ProductAbstractStoreValidatorRule($storeReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $response->getErrors());
    }

    /**
     * @param array<string> $unknownStoreNames
     *
     * @return \Spryker\Zed\Product\Business\Reader\StoreReaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStoreReaderMock(array $unknownStoreNames): StoreReaderInterface
    {
        $storeReaderMock = $this->createMock(StoreReaderInterface::class);
        $storeReaderMock->method('getMissingStoreNames')->willReturn($unknownStoreNames);

        return $storeReaderMock;
    }

    /**
     * @param array<string, array<string>> $storeNamesBySku
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer
     */
    protected function createRequest(array $storeNamesBySku): ProductAbstractCollectionRequestTransfer
    {
        $productAbstractCollectionRequestTransfer = new ProductAbstractCollectionRequestTransfer();

        foreach ($storeNamesBySku as $sku => $storeNames) {
            $storeRelationTransfer = new StoreRelationTransfer();

            foreach ($storeNames as $storeName) {
                $storeRelationTransfer->addStores((new StoreTransfer())->setName($storeName));
            }

            $productAbstractCollectionRequestTransfer->addProductAbstract(
                (new ProductAbstractTransfer())
                    ->setSku((string)$sku)
                    ->setStoreRelation($storeRelationTransfer),
            );
        }

        return $productAbstractCollectionRequestTransfer;
    }
}
