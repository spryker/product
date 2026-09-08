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
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractCreateValidatorRule;
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
 * @group ProductAbstractCreateValidatorRuleTest
 *
 * Add your own group annotations below this line
 */
class ProductAbstractCreateValidatorRuleTest extends Unit
{
    protected const string NEW_SKU = 'new-abstract-sku';

    protected const string PERSISTED_SKU = 'persisted-abstract-sku';

    protected const string OTHER_NEW_SKU = 'other-new-abstract-sku';

    protected const string LOCALE_NAME = 'en_US';

    public function testValidateReturnsNoErrorsForNewSku(): void
    {
        // Arrange
        $rule = $this->createRule([]);
        $request = $this->createRequest([(new ProductAbstractTransfer())->setSku(static::NEW_SKU)]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $response->getErrors());
    }

    public function testValidateReturnsErrorWhenSkuAlreadyExists(): void
    {
        // Arrange
        $rule = $this->createRule([static::PERSISTED_SKU]);
        $request = $this->createRequest([(new ProductAbstractTransfer())->setSku(static::PERSISTED_SKU)]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $errorTransfer = $response->getErrors()->offsetGet(0);
        $this->assertSame(static::PERSISTED_SKU, $errorTransfer->getEntityIdentifier());
        $this->assertSame(
            sprintf('Abstract product with SKU "%s" already exists.', static::PERSISTED_SKU),
            $errorTransfer->getMessage(),
        );
    }

    public function testValidateReturnsErrorForLocalizedAttributesWithoutLocaleName(): void
    {
        // Arrange
        $rule = $this->createRule([]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())
                ->setSku(static::NEW_SKU)
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
        $rule = $this->createRule([]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())
                ->setSku(static::NEW_SKU)
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
        $rule = $this->createRule([]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())
                ->setSku(static::NEW_SKU)
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

    public function testValidateReportsEveryViolationOfTheSameProductAbstract(): void
    {
        // Arrange
        $rule = $this->createRule([static::PERSISTED_SKU]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())
                ->setSku(static::PERSISTED_SKU)
                ->addLocalizedAttributes(new LocalizedAttributesTransfer())
                ->setNewFrom('2027-12-31 00:00:00')
                ->setNewTo('2027-01-01 00:00:00'),
        ]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(3, $response->getErrors());
    }

    public function testValidateReturnsErrorPerProductAbstract(): void
    {
        // Arrange
        $rule = $this->createRule([static::PERSISTED_SKU]);
        $request = $this->createRequest([
            (new ProductAbstractTransfer())->setSku(static::PERSISTED_SKU),
            (new ProductAbstractTransfer())->setSku(static::NEW_SKU),
        ]);

        // Act
        $response = $rule->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $response->getErrors());
        $this->assertSame(static::PERSISTED_SKU, $response->getErrors()->offsetGet(0)->getEntityIdentifier());
    }

    public function testValidateResolvesPersistedSkusWithASingleQuery(): void
    {
        // Arrange
        $productAbstractSkuReaderMock = $this->createMock(ProductAbstractSkuReaderInterface::class);
        $productAbstractSkuReaderMock
            ->expects($this->once())
            ->method('getExistingSkus')
            ->with($this->equalTo([static::NEW_SKU, static::OTHER_NEW_SKU]))
            ->willReturn([]);

        $request = $this->createRequest([
            (new ProductAbstractTransfer())->setSku(static::NEW_SKU),
            (new ProductAbstractTransfer())->setSku(static::OTHER_NEW_SKU),
        ]);

        // Act
        (new ProductAbstractCreateValidatorRule($productAbstractSkuReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());
    }

    public function testValidatePassesEmptySkusWhenNoSkuIsProvided(): void
    {
        // Arrange
        $productAbstractSkuReaderMock = $this->createMock(ProductAbstractSkuReaderInterface::class);
        $productAbstractSkuReaderMock
            ->expects($this->once())
            ->method('getExistingSkus')
            ->with($this->equalTo([]))
            ->willReturn([]);

        $request = $this->createRequest([new ProductAbstractTransfer()]);

        // Act
        $response = (new ProductAbstractCreateValidatorRule($productAbstractSkuReaderMock))
            ->validate($request, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $response->getErrors());
    }

    /**
     * @param array<string> $existingSkus
     *
     * @return \Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractCreateValidatorRule
     */
    protected function createRule(array $existingSkus): ProductAbstractCreateValidatorRule
    {
        $productAbstractSkuReaderMock = $this->createMock(ProductAbstractSkuReaderInterface::class);
        $productAbstractSkuReaderMock
            ->method('getExistingSkus')
            ->willReturn(array_fill_keys($existingSkus, true));

        return new ProductAbstractCreateValidatorRule($productAbstractSkuReaderMock);
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
