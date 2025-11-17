<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Communication\Plugin\ProductManagement;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Product\Communication\Plugin\ProductManagement\StoreRelationAbstractProductReadinessProviderPlugin;
use Spryker\Zed\Product\Communication\ProductCommunicationFactory;
use Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface;
use Spryker\Zed\Product\ProductDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group StoreRelationAbstractProductReadinessExpanderPluginTest
 * Add your own group annotations below this line
 */
class StoreRelationAbstractProductReadinessExpanderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideCreatesTwoReadinessEntriesPerAbstract(): void
    {
        // Arrange
        $this->tester->setDependency(ProductDependencyProvider::FACADE_STORE, $this->createStoreFacadeMock());

        $storeRelation = (new StoreRelationTransfer())
            ->addStores((new StoreTransfer())->setName('DE'));

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setStoreRelation($storeRelation);

        $productAbstractReadinessRequestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductAbstract($productAbstractTransfer);

        // Act
        $result = (new StoreRelationAbstractProductReadinessProviderPlugin())->provide(
            $productAbstractReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(2, $result);

        $hasRelationReadiness = $result[0];
        $this->assertSame('Has relation with stores', $hasRelationReadiness->getTitle());
        $this->assertContains('DE', $hasRelationReadiness->getValues());

        $noRelationReadiness = $result[1];
        $this->assertSame('Has no relation with stores', $noRelationReadiness->getTitle());
        $this->assertContains('US', $noRelationReadiness->getValues());
    }

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToStoreFacadeInterface $storeFacadeMock
     *
     * @return \Spryker\Zed\Product\Communication\ProductCommunicationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createFactoryMock(
        ProductToStoreInterface $storeFacadeMock
    ): ProductCommunicationFactory {
        $factoryMock = $this->getMockBuilder(ProductCommunicationFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getStoreFacade'])
            ->getMock();

        $factoryMock->method('getStoreFacade')->willReturn($storeFacadeMock);

        return $factoryMock;
    }

    /**
     * @return \Spryker\Zed\ProductManagement\Dependency\Facade\ProductToStoreFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStoreFacadeMock(): ProductToStoreInterface
    {
        $storeFacadeMock = $this->getMockBuilder(ProductToStoreInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $allStores = [
            (new StoreTransfer())->setName('DE'),
            (new StoreTransfer())->setName('US'),
        ];

        $storeFacadeMock->method('getAllStores')->willReturn($allStores);

        return $storeFacadeMock;
    }
}
