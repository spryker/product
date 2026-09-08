<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Validator\Rule;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractUpdateValidatorRule;
use Spryker\Zed\Product\Business\Reader\ProductAbstractSkuReaderInterface;

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
 * @group ProductAbstractUpdateValidatorRuleTest
 *
 * Add your own group annotations below this line
 */
class ProductAbstractUpdateValidatorRuleTest extends Unit
{
    protected const string PERSISTED_SKU = 'persisted-abstract-sku';

    protected const string UNKNOWN_SKU = 'unknown-abstract-sku';

    protected const string OTHER_PERSISTED_SKU = 'other-persisted-abstract-sku';

    protected const string LOCALE_NAME = 'en_US';

    public function testValidateReturnsNoErrorsForPersistedSku(): void
    {
        // Arrange
        $rule = $this->createRule([static::PERSISTED_SKU]);
        $request = $this->createRequest([(new ProductAbstractTransfer())->setSku(static::PERSISTED_SKU)]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $response->getErrors());
    }

    public function testValidateReturnsErrorWhenSkuDoesNotExist(): void
    {
        // Arrange
        $rule = $this->createRule([]);
        $request = $this->createRequest([(new ProductAbstractTransfer())->setSku(static::UNKNOWN_SKU)]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $errorTransfer = $response->getErrors()->offsetGet(0);
        $this->assertSame(static::UNKNOWN_SKU, $errorTransfer->getEntityIdentifier());
        $this->assertSame(
            sprintf('Abstract product with SKU "%s" does not exist.', static::UNKNOWN_SKU),
            $errorTransfer->getMessage(),
        );
    }

    public function testValidateReturnsErrorWhenSkuIsMissing(): void
    {
        // Arrange
        $rule = $this->createRule([]);
        $request = $this->createRequest([new ProductAbstractTransfer()]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $errorTransfer = $response->getErrors()->offsetGet(0);
        $this->assertNull($errorTransfer->getEntityIdentifier());
        $this->assertSame('Abstract product with SKU "" does not exist.', $errorTransfer->getMessage());
    }

    public function testValidateSkipsFurtherChecksWhenSkuDoesNotExist(): void
    {
        // Arrange
        $rule = $this->createRule([]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())
                ->setSku(static::UNKNOWN_SKU)
                ->addLocalizedAttributes(new LocalizedAttributesTransfer())
                ->setNewFrom('2027-12-31 00:00:00')
                ->setNewTo('2027-01-01 00:00:00'),
        ]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $this->assertSame(
            sprintf('Abstract product with SKU "%s" does not exist.', static::UNKNOWN_SKU),
            $response->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testValidateReturnsErrorForLocalizedAttributesWithoutLocaleName(): void
    {
        // Arrange
        $rule = $this->createRule([static::PERSISTED_SKU]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())
                ->setSku(static::PERSISTED_SKU)
                ->addLocalizedAttributes(new LocalizedAttributesTransfer()),
        ]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $this->assertSame(
            'Field "localeName" is required for every localized attributes entry.',
            $response->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testValidateReturnsNoErrorWhenLocalizedAttributesCarryLocaleName(): void
    {
        // Arrange
        $rule = $this->createRule([static::PERSISTED_SKU]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())
                ->setSku(static::PERSISTED_SKU)
                ->addLocalizedAttributes(
                    (new LocalizedAttributesTransfer())->setLocale((new LocaleTransfer())->setLocaleName(static::LOCALE_NAME)),
                ),
        ]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $response->getErrors());
    }

    public function testValidateReturnsErrorWhenNewFromIsLaterThanNewTo(): void
    {
        // Arrange
        $rule = $this->createRule([static::PERSISTED_SKU]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())
                ->setSku(static::PERSISTED_SKU)
                ->setNewFrom('2027-12-31 00:00:00')
                ->setNewTo('2027-01-01 00:00:00'),
        ]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $this->assertSame(
            'Field "newFrom" must not be later than "newTo".',
            $response->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testValidateReturnsErrorPerProductAbstract(): void
    {
        // Arrange
        $rule = $this->createRule([static::PERSISTED_SKU, static::OTHER_PERSISTED_SKU]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())->setSku(static::PERSISTED_SKU),
            (new ProductAbstractTransfer())->setSku(static::UNKNOWN_SKU),
        ]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $this->assertSame(static::UNKNOWN_SKU, $response->getErrors()->offsetGet(0)->getEntityIdentifier());
    }

    public function testValidateResolvesPersistedSkusWithASingleQuery(): void
    {
        // Arrange
        $productAbstractSkuReaderMock = $this->createMock(ProductAbstractSkuReaderInterface::class);
        $productAbstractSkuReaderMock
            ->expects($this->once())
            ->method('getExistingSkus')
            ->with($this->equalTo([static::PERSISTED_SKU, static::OTHER_PERSISTED_SKU]))
            ->willReturn([static::PERSISTED_SKU => true, static::OTHER_PERSISTED_SKU => true]);

        $request = $this->createRequest([
            (new ProductAbstractTransfer())->setSku(static::PERSISTED_SKU),
            (new ProductAbstractTransfer())->setSku(static::OTHER_PERSISTED_SKU),
        ]);

        // Act
        (new ProductAbstractUpdateValidatorRule($productAbstractSkuReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());
    }

    /**
     * @param array<string> $existingSkus
     *
     * @return \Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractUpdateValidatorRule
     */
    protected function createRule(array $existingSkus): ProductAbstractUpdateValidatorRule
    {
        $productAbstractSkuReaderMock = $this->createMock(ProductAbstractSkuReaderInterface::class);
        $productAbstractSkuReaderMock
            ->method('getExistingSkus')
            ->willReturn(array_fill_keys($existingSkus, true));

        return new ProductAbstractUpdateValidatorRule($productAbstractSkuReaderMock);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfers
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer
     */
    protected function createRequest(array $productAbstractTransfers): ProductAbstractCollectionRequestTransfer
    {
        $productAbstractCollectionRequestTransfer = new ProductAbstractCollectionRequestTransfer();

        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $productAbstractCollectionRequestTransfer->addProductAbstract($productAbstractTransfer);
        }

        return $productAbstractCollectionRequestTransfer;
    }
}
