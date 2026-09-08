<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Validator\Rule;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductConcreteLocaleValidatorRule;
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
 * @group ProductConcreteLocaleValidatorRuleTest
 *
 * Add your own group annotations below this line
 */
class ProductConcreteLocaleValidatorRuleTest extends Unit
{
    protected const string INSTALLED_LOCALE_NAME = 'en_US';

    protected const string UNKNOWN_LOCALE_NAME = 'zz_ZZ';

    protected const string OTHER_UNKNOWN_LOCALE_NAME = 'yy_YY';

    protected const string PRODUCT_CONCRETE_SKU = 'concrete-sku';

    protected const string ERROR_MESSAGE_PATTERN = 'Locale "%s" is not installed.';

    public function testValidateReturnsNoErrorsWhenLocaleIsInstalled(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([]);
        $productConcreteCollectionRequestTransfer = $this->createRequest([static::INSTALLED_LOCALE_NAME]);

        // Act
        $productConcreteCollectionResponseTransfer = (new ProductConcreteLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productConcreteCollectionRequestTransfer, new ProductConcreteCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $productConcreteCollectionResponseTransfer->getErrors());
    }

    public function testValidateReturnsErrorWithMessageForUnknownLocale(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([static::UNKNOWN_LOCALE_NAME]);
        $productConcreteCollectionRequestTransfer = $this->createRequest([static::UNKNOWN_LOCALE_NAME]);

        // Act
        $productConcreteCollectionResponseTransfer = (new ProductConcreteLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productConcreteCollectionRequestTransfer, new ProductConcreteCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
        $errorTransfer = $productConcreteCollectionResponseTransfer->getErrors()->offsetGet(0);
        $this->assertSame(static::PRODUCT_CONCRETE_SKU, $errorTransfer->getEntityIdentifier());
        $this->assertSame(sprintf(static::ERROR_MESSAGE_PATTERN, static::UNKNOWN_LOCALE_NAME), $errorTransfer->getMessage());
    }

    public function testValidateReturnsErrorForUnknownImageSetLocale(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([static::UNKNOWN_LOCALE_NAME]);
        $productConcreteCollectionRequestTransfer = $this->createRequest([], [static::UNKNOWN_LOCALE_NAME]);

        // Act
        $productConcreteCollectionResponseTransfer = (new ProductConcreteLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productConcreteCollectionRequestTransfer, new ProductConcreteCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
        $this->assertSame(
            sprintf(static::ERROR_MESSAGE_PATTERN, static::UNKNOWN_LOCALE_NAME),
            $productConcreteCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    public function testValidateReturnsErrorPerUnknownLocaleAcrossAttributesAndImageSets(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([
            static::UNKNOWN_LOCALE_NAME,
            static::OTHER_UNKNOWN_LOCALE_NAME,
        ]);
        $productConcreteCollectionRequestTransfer = $this->createRequest(
            [static::UNKNOWN_LOCALE_NAME],
            [static::OTHER_UNKNOWN_LOCALE_NAME],
        );

        // Act
        $productConcreteCollectionResponseTransfer = (new ProductConcreteLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productConcreteCollectionRequestTransfer, new ProductConcreteCollectionResponseTransfer());

        // Assert
        $this->assertCount(2, $productConcreteCollectionResponseTransfer->getErrors());
    }

    public function testValidateReturnsSingleErrorWhenAttributesAndImageSetsShareUnknownLocale(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createUnknownLocaleNameReaderMock([static::UNKNOWN_LOCALE_NAME]);
        $productConcreteCollectionRequestTransfer = $this->createRequest(
            [static::UNKNOWN_LOCALE_NAME],
            [static::UNKNOWN_LOCALE_NAME],
        );

        // Act
        $productConcreteCollectionResponseTransfer = (new ProductConcreteLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productConcreteCollectionRequestTransfer, new ProductConcreteCollectionResponseTransfer());

        // Assert
        $this->assertCount(1, $productConcreteCollectionResponseTransfer->getErrors());
    }

    public function testValidateResolvesUnknownLocalesOnceWithMergedLocaleNames(): void
    {
        // Arrange
        $unknownLocaleNameReaderMock = $this->createMock(LocaleReaderInterface::class);
        $unknownLocaleNameReaderMock
            ->expects($this->once())
            ->method('getMissingLocaleNames')
            ->with($this->equalTo([static::INSTALLED_LOCALE_NAME, static::UNKNOWN_LOCALE_NAME]))
            ->willReturn([]);

        $productConcreteCollectionRequestTransfer = $this->createRequest(
            [static::INSTALLED_LOCALE_NAME],
            [static::UNKNOWN_LOCALE_NAME],
        );

        // Act
        (new ProductConcreteLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productConcreteCollectionRequestTransfer, new ProductConcreteCollectionResponseTransfer());
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

        $productConcreteCollectionRequestTransfer = (new ProductConcreteCollectionRequestTransfer())
            ->addProduct((new ProductConcreteTransfer())->setSku(static::PRODUCT_CONCRETE_SKU));

        // Act
        $productConcreteCollectionResponseTransfer = (new ProductConcreteLocaleValidatorRule($unknownLocaleNameReaderMock))
            ->validate($productConcreteCollectionRequestTransfer, new ProductConcreteCollectionResponseTransfer());

        // Assert
        $this->assertCount(0, $productConcreteCollectionResponseTransfer->getErrors());
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
     * @param array<string> $localizedAttributesLocaleNames
     * @param array<string> $imageSetLocaleNames
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionRequestTransfer
     */
    protected function createRequest(array $localizedAttributesLocaleNames, array $imageSetLocaleNames = []): ProductConcreteCollectionRequestTransfer
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())->setSku(static::PRODUCT_CONCRETE_SKU);

        foreach ($localizedAttributesLocaleNames as $localeName) {
            $productConcreteTransfer->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())->setLocale((new LocaleTransfer())->setLocaleName($localeName)),
            );
        }

        foreach ($imageSetLocaleNames as $localeName) {
            $productConcreteTransfer->addImageSet(
                (new ProductImageSetTransfer())->setLocale((new LocaleTransfer())->setLocaleName($localeName)),
            );
        }

        return (new ProductConcreteCollectionRequestTransfer())->addProduct($productConcreteTransfer);
    }
}
