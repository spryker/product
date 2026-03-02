<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Communication\Plugin\ProductManagement;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Communication\Plugin\ProductManagement\IsSearchableForLocaleAbstractProductReadinessProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group IsSearchableForLocaleAbstractProductReadinessExpanderPluginTest
 * Add your own group annotations below this line
 */
class IsSearchableForLocaleAbstractProductReadinessExpanderPluginTest extends Unit
{
    public function testProvideAggregatesSearchableLocalesAcrossAllConcretes(): void
    {
        // Arrange
        $productConcretes = new ArrayObject([
            (new ProductConcreteTransfer())->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())
                    ->setLocale((new LocaleTransfer())->setLocaleName('en_US'))
                    ->setIsSearchable(true),
            ),
            (new ProductConcreteTransfer())->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())
                    ->setLocale((new LocaleTransfer())->setLocaleName('de_DE'))
                    ->setIsSearchable(false),
            ),
        ]);

        $productAbstractReadinessRequestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductConcretes($productConcretes);

        // Act
        $result = (new IsSearchableForLocaleAbstractProductReadinessProviderPlugin())->provide(
            $productAbstractReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(2, $result);

        $searchableReadiness = $result[0];
        $this->assertSame('At least one concrete has searchable flag in locales', $searchableReadiness->getTitle());
        $this->assertContains('en_US', $searchableReadiness->getValues());

        $notSearchableReadiness = $result[1];
        $this->assertSame('No concrete has searchable flag in locales', $notSearchableReadiness->getTitle());
        $this->assertContains('de_DE', $notSearchableReadiness->getValues());
    }

    public function testProvideReturnsEarlyWhenNoConcretes(): void
    {
        // Arrange
        $productAbstractReadinessRequestTransfer = (new ProductAbstractReadinessRequestTransfer())
            ->setProductConcretes(new ArrayObject());

        // Act
        $result = (new IsSearchableForLocaleAbstractProductReadinessProviderPlugin())->provide(
            $productAbstractReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(0, $result);
    }
}
