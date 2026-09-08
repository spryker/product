<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Reader;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Product\Business\Reader\StoreReader;
use Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Reader
 * @group StoreReaderTest
 *
 * Add your own group annotations below this line
 */
class StoreReaderTest extends Unit
{
    protected const string KNOWN_STORE_NAME = 'DE';

    protected const string OTHER_KNOWN_STORE_NAME = 'AT';

    protected const string UNKNOWN_STORE_NAME = 'ZZ';

    public function testGetMissingStoreNamesReturnsEmptyArrayForEmptyInputWithoutResolvingStores(): void
    {
        // Arrange
        $storeFacadeMock = $this->createMock(ProductToStoreInterface::class);
        $storeFacadeMock->expects($this->never())->method('getAllStores');

        // Act
        $missingStoreNames = (new StoreReader($storeFacadeMock))->getMissingStoreNames([]);

        // Assert
        $this->assertSame([], $missingStoreNames);
    }

    public function testGetMissingStoreNamesReturnsEmptyArrayWhenAllStoresAreKnown(): void
    {
        // Arrange
        $storeReader = new StoreReader($this->createStoreFacadeMock());

        // Act
            $missingStoreNames = $storeReader->getMissingStoreNames([
            static::KNOWN_STORE_NAME,
            static::OTHER_KNOWN_STORE_NAME,
            ]);

        // Assert
        $this->assertSame([], $missingStoreNames);
    }

    public function testGetMissingStoreNamesReturnsOnlyStoresThatDoNotExist(): void
    {
        // Arrange
        $storeReader = new StoreReader($this->createStoreFacadeMock());

        // Act
        $missingStoreNames = $storeReader->getMissingStoreNames([
            static::KNOWN_STORE_NAME,
            static::UNKNOWN_STORE_NAME,
        ]);

        // Assert
        $this->assertSame([static::UNKNOWN_STORE_NAME], $missingStoreNames);
    }

    public function testGetMissingStoreNamesResolvesStoresWithASingleCall(): void
    {
        // Arrange
        $storeFacadeMock = $this->createStoreFacadeMock();
        $storeFacadeMock->expects($this->once())->method('getAllStores');

        // Act
        (new StoreReader($storeFacadeMock))->getMissingStoreNames([
            static::KNOWN_STORE_NAME,
            static::UNKNOWN_STORE_NAME,
        ]);
    }

    public function testGetMissingStoreNamesIgnoresKnownStoresWithoutName(): void
    {
        // Arrange
        $storeFacadeMock = $this->createMock(ProductToStoreInterface::class);
        $storeFacadeMock->method('getAllStores')->willReturn([
            new StoreTransfer(),
            (new StoreTransfer())->setName(''),
            (new StoreTransfer())->setName(static::KNOWN_STORE_NAME),
        ]);

        // Act
        $missingStoreNames = (new StoreReader($storeFacadeMock))->getMissingStoreNames([
            static::KNOWN_STORE_NAME,
            static::UNKNOWN_STORE_NAME,
        ]);

        // Assert
        $this->assertSame([static::UNKNOWN_STORE_NAME], $missingStoreNames);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStoreFacadeMock(): ProductToStoreInterface
    {
        $storeFacadeMock = $this->createMock(ProductToStoreInterface::class);
        $storeFacadeMock->method('getAllStores')->willReturn([
            (new StoreTransfer())->setName(static::KNOWN_STORE_NAME),
            (new StoreTransfer())->setName(static::OTHER_KNOWN_STORE_NAME),
        ]);

        return $storeFacadeMock;
    }
}
