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
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractLocaleValidatorRule;
use Spryker\Zed\Product\Business\Reader\LocaleReaderInterface;

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
 * @group ProductAbstractLocaleValidatorRuleTest
 *
 * Add your own group annotations below this line
 */
class ProductAbstractLocaleValidatorRuleTest extends Unit
{
    protected const string INSTALLED_LOCALE_NAME = 'en_US';

    protected const string UNKNOWN_LOCALE_NAME = 'zz_ZZ';

    protected const string PRODUCT_ABSTRACT_SKU = 'abstract-sku';

    protected const string OTHER_PRODUCT_ABSTRACT_SKU = 'other-abstract-sku';

    protected const string ERROR_MESSAGE_PATTERN = 'Locale "%s" is not installed.';

    public function testValidateReturnsNoErrorsWhenLocaleIsInstalled(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([]);
        $productAbstractCollectionRequestTransfer = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::INSTALLED_LOCALE_NAME],
        ]);

        // Act
        $productAbstractCollectionResponseTransfer = (new ProductAbstractLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productAbstractCollectionRequestTransfer, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $productAbstractCollectionResponseTransfer->getErrors());
    }

    public function testValidateReturnsErrorWithMessageForUnknownLocale(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([static::UNKNOWN_LOCALE_NAME]);
        $productAbstractCollectionRequestTransfer = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_LOCALE_NAME],
        ]);

        // Act
        $productAbstractCollectionResponseTransfer = (new ProductAbstractLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productAbstractCollectionRequestTransfer, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $productAbstractCollectionResponseTransfer->getErrors());
        $errorTransfer = $productAbstractCollectionResponseTransfer->getErrors()->offsetGet(0);
        $this->assertSame(static::PRODUCT_ABSTRACT_SKU, $errorTransfer->getEntityIdentifier());
        $this->assertSame(sprintf(static::ERROR_MESSAGE_PATTERN, static::UNKNOWN_LOCALE_NAME), $errorTransfer->getMessage());
    }

    public function testValidateReturnsErrorsOnlyForUnknownLocales(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([static::UNKNOWN_LOCALE_NAME]);
        $productAbstractCollectionRequestTransfer = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::INSTALLED_LOCALE_NAME, static::UNKNOWN_LOCALE_NAME],
        ]);

        // Act
        $productAbstractCollectionResponseTransfer = (new ProductAbstractLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productAbstractCollectionRequestTransfer, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $productAbstractCollectionResponseTransfer->getErrors());
        $this->assertSame(
            sprintf(static::ERROR_MESSAGE_PATTERN, static::UNKNOWN_LOCALE_NAME),
            $productAbstractCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testValidateReturnsErrorPerProductAbstractForSameUnknownLocale(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([static::UNKNOWN_LOCALE_NAME]);
        $productAbstractCollectionRequestTransfer = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_LOCALE_NAME],
            static::OTHER_PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_LOCALE_NAME],
        ]);

        // Act
        $productAbstractCollectionResponseTransfer = (new ProductAbstractLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productAbstractCollectionRequestTransfer, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(2, $productAbstractCollectionResponseTransfer->getErrors());
        $entityIdentifiers = [];

        foreach ($productAbstractCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $entityIdentifiers[] = $errorTransfer->getEntityIdentifier();
        }

        $this->assertEqualsCanonicalizing(
            [static::PRODUCT_ABSTRACT_SKU, static::OTHER_PRODUCT_ABSTRACT_SKU],
            $entityIdentifiers,
        );
    }

    public function testValidateReturnsSingleErrorForDuplicatedUnknownLocale(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([static::UNKNOWN_LOCALE_NAME]);
        $productAbstractCollectionRequestTransfer = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_LOCALE_NAME, static::UNKNOWN_LOCALE_NAME],
        ]);

        // Act
        $productAbstractCollectionResponseTransfer = (new ProductAbstractLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productAbstractCollectionRequestTransfer, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $productAbstractCollectionResponseTransfer->getErrors());
    }

    public function testValidateResolvesUnknownLocalesOnceForTheWholeCollection(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createMock(LocaleReaderInterface::class);
        $unknownLocaleNameReaderMock
            ->expects($this->once())
            ->method('getMissingLocaleNames')
            ->with($this->equalTo([static::INSTALLED_LOCALE_NAME, static::UNKNOWN_LOCALE_NAME]))
            ->willReturn([]);

        $productAbstractCollectionRequestTransfer = $this->createRequest([
            static::PRODUCT_ABSTRACT_SKU => [static::INSTALLED_LOCALE_NAME],
            static::OTHER_PRODUCT_ABSTRACT_SKU => [static::UNKNOWN_LOCALE_NAME],
        ]);

        // Act
        (new ProductAbstractLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productAbstractCollectionRequestTransfer, new ProductAbstractCollectionResponseTransfer());
    }

    public function testValidatePassesEmptyLocaleNamesWhenNoLocaleNamesProvided(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createMock(LocaleReaderInterface::class);
        $unknownLocaleNameReaderMock
            ->expects($this->once())
            ->method('getMissingLocaleNames')
            ->with($this->equalTo([]))
            ->willReturn([]);

        $productAbstractCollectionRequestTransfer = (new ProductAbstractCollectionRequestTransfer())
            ->addProductAbstract((new ProductAbstractTransfer())->setSku(static::PRODUCT_ABSTRACT_SKU));

        // Act
        $productAbstractCollectionResponseTransfer = (new ProductAbstractLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productAbstractCollectionRequestTransfer, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $productAbstractCollectionResponseTransfer->getErrors());
    }

    public function testValidateIgnoresLocalizedAttributesWithoutLocaleName(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([]);
        $productAbstractCollectionRequestTransfer = (new ProductAbstractCollectionRequestTransfer())
            ->addProductAbstract(
                (new ProductAbstractTransfer())
                    ->setSku(static::PRODUCT_ABSTRACT_SKU)
                    ->addLocalizedAttributes(new LocalizedAttributesTransfer()),
            );

        // Act
        $productAbstractCollectionResponseTransfer = (new ProductAbstractLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productAbstractCollectionRequestTransfer, new ProductAbstractCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $productAbstractCollectionResponseTransfer->getErrors());
    }

    /**
     * @param array<string> $unknownLocaleNames
     *
     * @return \Spryker\Zed\Product\Business\Reader\LocaleReaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createUnknownLocaleNameReaderMock(array $unknownLocaleNames): LocaleReaderInterface
    {
        $unknownLocaleNameReaderMock = $this->createMock(LocaleReaderInterface::class);
        $unknownLocaleNameReaderMock->method('getMissingLocaleNames')->willReturn($unknownLocaleNames);

        return $unknownLocaleNameReaderMock;
    }

    /**
     * @param array<string, array<string>> $localeNamesBySku
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionRequestTransfer
     */
    protected function createRequest(array $localeNamesBySku): ProductAbstractCollectionRequestTransfer
    {
        $productAbstractCollectionRequestTransfer = new ProductAbstractCollectionRequestTransfer();

        foreach ($localeNamesBySku as $sku => $localeNames) {
            $productAbstractTransfer = (new ProductAbstractTransfer())->setSku((string)$sku);

            foreach ($localeNames as $localeName) {
                $productAbstractTransfer->addLocalizedAttributes(
                    (new LocalizedAttributesTransfer())->setLocale((new LocaleTransfer())->setLocaleName($localeName)),
                );
            }

            $productAbstractCollectionRequestTransfer->addProductAbstract($productAbstractTransfer);
        }

        return $productAbstractCollectionRequestTransfer;
    }
}
