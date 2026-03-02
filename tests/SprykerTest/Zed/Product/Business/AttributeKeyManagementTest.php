<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Orm\Zed\Product\Persistence\SpyProductAttributeKey;
use Spryker\Zed\Product\Business\ProductFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group AttributeKeyManagementTest
 * Add your own group annotations below this line
 */
class AttributeKeyManagementTest extends Unit
{
    /**
     * @var string
     */
    public const UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB = 'unique_attribute_key_not_existing_in_db';

    /**
     * @var string
     */
    public const CHANGED_UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB = 'changed_unique_attribute_key_not_existing_in_db';

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacade;

    public function setUp(): void
    {
        parent::setUp();

        $this->productFacade = new ProductFacade();
    }

    public function testHasProductAttributeKeyReturnsFalseIfKeyDoesNotExist(): void
    {
        $result = $this->productFacade->hasProductAttributeKey(static::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $this->assertFalse($result);
    }

    public function testHasProductAttributeKeyReturnsTrueIfKeyExists(): void
    {
        $productAttributeKeyEntity = $this->createAttributeKeyEntity(static::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $result = $this->productFacade->hasProductAttributeKey($productAttributeKeyEntity->getKey());

        $this->assertTrue($result);
    }

    public function testGetProductAttributeKeyReturnsNullIfKeyDoesNotExist(): void
    {
        $productAttributeKeyTransfer = $this->productFacade->findProductAttributeKey(static::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $this->assertNull($productAttributeKeyTransfer);
    }

    public function testGetProductAttributeKeyReturnsTransferIfKeyExist(): void
    {
        $productAttributeKeyEntity = $this->createAttributeKeyEntity(static::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $productAttributeKeyTransfer = $this->productFacade->findProductAttributeKey($productAttributeKeyEntity->getKey());

        $this->assertEquals($productAttributeKeyEntity->toArray(), $productAttributeKeyTransfer->toArray());
    }

    public function testCreateProductAttributeKey(): void
    {
        $productAttributeKeyTransfer = new ProductAttributeKeyTransfer();
        $productAttributeKeyTransfer->setKey(static::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $productAttributeKeyTransfer = $this->productFacade->createProductAttributeKey($productAttributeKeyTransfer);

        $this->assertNotNull($productAttributeKeyTransfer->getIdProductAttributeKey());
    }

    public function testUpdateProductAttributeKey(): void
    {
        $productAttributeKeyEntity = $this->createAttributeKeyEntity(static::UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $productAttributeKeyTransfer = new ProductAttributeKeyTransfer();
        $productAttributeKeyTransfer
            ->setIdProductAttributeKey($productAttributeKeyEntity->getIdProductAttributeKey())
            ->setKey(static::CHANGED_UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB);

        $productAttributeKeyTransfer = $this->productFacade->updateProductAttributeKey($productAttributeKeyTransfer);

        $this->assertSame(static::CHANGED_UNIQUE_ATTRIBUTE_KEY_NOT_EXISTING_IN_DB, $productAttributeKeyTransfer->getKey());
    }

    protected function createAttributeKeyEntity(string $key): SpyProductAttributeKey
    {
        $productAttributeKeyEntity = new SpyProductAttributeKey();
        $productAttributeKeyEntity->setKey($key);
        $productAttributeKeyEntity->save();

        return $productAttributeKeyEntity;
    }
}
