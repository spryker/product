<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Reader;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Product\Business\Reader\LocaleReader;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Reader
 * @group LocaleReaderTest
 *
 * Add your own group annotations below this line
 */
class LocaleReaderTest extends Unit
{
    protected const string INSTALLED_LOCALE_NAME = 'en_US';

    protected const string OTHER_INSTALLED_LOCALE_NAME = 'de_DE';

    protected const string UNKNOWN_LOCALE_NAME = 'zz_ZZ';

    public function testGetMissingLocaleNamesReturnsEmptyArrayForEmptyInputWithoutQuerying(): void
    {
        // Arrange
        $localeFacadeMock = $this->createMock(ProductToLocaleInterface::class);
        $localeFacadeMock->expects($this->never())->method('getLocaleCollection');

        // Act
        $unknownLocaleNames = (new LocaleReader($localeFacadeMock))->getMissingLocaleNames([]);

        // Assert
        $this->assertSame([], $unknownLocaleNames);
    }

    public function testGetUnknownLocaleNamesReturnsEmptyArrayWhenAllLocalesAreInstalled(): void
    {
        // Arrange
        $unknownLocaleNameReader = new LocaleReader($this->createLocaleFacadeMock());

        // Act
        $unknownLocaleNames = $unknownLocaleNameReader->getMissingLocaleNames([
            static::INSTALLED_LOCALE_NAME,
            static::OTHER_INSTALLED_LOCALE_NAME,
        ]);

        // Assert
        $this->assertSame([], $unknownLocaleNames);
    }

    public function testGetMissingLocaleNamesReturnsOnlyLocalesThatAreNotInstalled(): void
    {
        // Arrange
        $unknownLocaleNameReader = new LocaleReader($this->createLocaleFacadeMock());

        // Act
        $unknownLocaleNames = $unknownLocaleNameReader->getMissingLocaleNames([
            static::INSTALLED_LOCALE_NAME,
            static::UNKNOWN_LOCALE_NAME,
        ]);

        // Assert
        $this->assertSame([static::UNKNOWN_LOCALE_NAME], $unknownLocaleNames);
    }

    public function testGetMissingLocaleNamesResolvesInstalledLocalesWithASingleCall(): void
    {
        // Arrange
        $localeFacadeMock = $this->createLocaleFacadeMock();
        $localeFacadeMock->expects($this->once())->method('getLocaleCollection');

        // Act
        (new LocaleReader($localeFacadeMock))->getMissingLocaleNames([
            static::INSTALLED_LOCALE_NAME,
            static::UNKNOWN_LOCALE_NAME,
        ]);
    }

    public function testGetMissingLocaleNamesIgnoresInstalledLocalesWithoutName(): void
    {
        // Arrange
        $localeFacadeMock = $this->createMock(ProductToLocaleInterface::class);
        $localeFacadeMock->method('getLocaleCollection')->willReturn([
            new LocaleTransfer(),
            (new LocaleTransfer())->setLocaleName(''),
            (new LocaleTransfer())->setLocaleName(static::INSTALLED_LOCALE_NAME),
        ]);

        // Act
        $missingLocaleNames = (new LocaleReader($localeFacadeMock))->getMissingLocaleNames([
            static::INSTALLED_LOCALE_NAME,
            static::UNKNOWN_LOCALE_NAME,
        ]);

        // Assert
        $this->assertSame([static::UNKNOWN_LOCALE_NAME], $missingLocaleNames);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createLocaleFacadeMock(): ProductToLocaleInterface
    {
        $localeFacadeMock = $this->createMock(ProductToLocaleInterface::class);
        $localeFacadeMock->method('getLocaleCollection')->willReturn([
            (new LocaleTransfer())->setLocaleName(static::INSTALLED_LOCALE_NAME),
            (new LocaleTransfer())->setLocaleName(static::OTHER_INSTALLED_LOCALE_NAME),
        ]);

        return $localeFacadeMock;
    }
}
