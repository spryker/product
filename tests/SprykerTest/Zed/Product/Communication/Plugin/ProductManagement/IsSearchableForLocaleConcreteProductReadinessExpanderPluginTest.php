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
use Generated\Shared\Transfer\ProductConcreteReadinessRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Communication\Plugin\ProductManagement\IsSearchableForLocaleConcreteProductReadinessProviderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group IsSearchableForLocaleConcreteProductReadinessExpanderPluginTest
 * Add your own group annotations below this line
 */
class IsSearchableForLocaleConcreteProductReadinessExpanderPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideCreatesTwoReadinessEntriesForConcrete(): void
    {
        // Arrange
        $productConcrete = (new ProductConcreteTransfer())
            ->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())
                    ->setLocale((new LocaleTransfer())->setLocaleName('en_US'))
                    ->setIsSearchable(true),
            )
            ->addLocalizedAttributes(
                (new LocalizedAttributesTransfer())
                    ->setLocale((new LocaleTransfer())->setLocaleName('de_DE'))
                    ->setIsSearchable(false),
            );

        $productConcreteReadinessRequestTransfer = (new ProductConcreteReadinessRequestTransfer())
            ->setProductConcrete($productConcrete);

        // Act
        $result = (new IsSearchableForLocaleConcreteProductReadinessProviderPlugin())->provide(
            $productConcreteReadinessRequestTransfer,
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(2, $result);

        $searchableReadiness = $result[0];
        $this->assertSame('Has searchable flag in locales', $searchableReadiness->getTitle());
        $this->assertContains('en_US', $searchableReadiness->getValues());

        $notSearchableReadiness = $result[1];
        $this->assertSame('No searchable flag in locales', $notSearchableReadiness->getTitle());
        $this->assertContains('de_DE', $notSearchableReadiness->getValues());
    }

    /**
     * @return void
     */
    public function testProvideReturnsEarlyWhenNoConcreteProvided(): void
    {
        // Act
        $result = (new IsSearchableForLocaleConcreteProductReadinessProviderPlugin())->provide(
            new ProductConcreteReadinessRequestTransfer(),
            new ArrayObject(),
        );

        // Assert
        $this->assertCount(0, $result);
    }
}
