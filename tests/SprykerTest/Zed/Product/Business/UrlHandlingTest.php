<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business;

use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Shared\Url\UrlConfig;
use Spryker\Zed\Product\Business\ProductBusinessFactory;
use Spryker\Zed\Product\ProductConfig;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group UrlHandlingTest
 * Add your own group annotations below this line
 */
class UrlHandlingTest extends FacadeTestAbstract
{
    protected function setUp(): void
    {
        parent::setUp();

        $configMock = $this->createMock(ProductConfig::class);
        $configMock->method('isFullLocaleNamesInUrlEnabled')->willReturn(true);
        $productBusinessFactory = new ProductBusinessFactory();
        $productBusinessFactory->setConfig($configMock);
        $this->productFacade->setFactory($productBusinessFactory);
    }

    public function testCreateProductUrlShouldCreateNewUrlForProductAbstract(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $expectedENUrl = (new LocalizedUrlTransfer())
        ->setLocale($this->locales['en_US'])
        ->setUrl('/en-us/product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
        ->setLocale($this->locales['de_DE'])
        ->setUrl('/de-de/product-name-dede-' . $idProductAbstract);

        $productUrl = $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl);
        $this->assertProductUrl($productUrl, $expectedDEUrl);
    }

    public function testCreateProductUrlShouldCreateNewUrlForProductAbstractBCCheck(): void
    {
        $configMock = $this->createMock(ProductConfig::class);
        $configMock->method('isFullLocaleNamesInUrlEnabled')->willReturn(false);
        $productBusinessFactory = new ProductBusinessFactory();
        $productBusinessFactory->setConfig($configMock);
        $this->productFacade->setFactory($productBusinessFactory);
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en/product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de/product-name-dede-' . $idProductAbstract);

        $productUrl = $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl);
        $this->assertProductUrl($productUrl, $expectedDEUrl);
    }

    public function testUpdateProductsUrlShouldCreateUrlsForProductAbstracts(): void
    {
        // Arrange
        $firstProductAbstractTransfer = clone $this->productAbstractTransfer;
        $firstProductAbstractTransfer->setSku($firstProductAbstractTransfer->getSku() . '_first');
        $secondProductAbstractTransfer = clone $this->productAbstractTransfer;
        $secondProductAbstractTransfer->setSku($firstProductAbstractTransfer->getSku() . '_second');
        $firstProductAbstractTransfer->setIdProductAbstract(
            $this->productAbstractManager->createProductAbstract($firstProductAbstractTransfer),
        );
        $secondProductAbstractTransfer->setIdProductAbstract(
            $this->productAbstractManager->createProductAbstract($secondProductAbstractTransfer),
        );
        $productAbstractTransfers = [
            $firstProductAbstractTransfer->getIdProductAbstract() => $firstProductAbstractTransfer,
            $secondProductAbstractTransfer->getIdProductAbstract() => $secondProductAbstractTransfer,
        ];

        // Act
        $urlTransfers = $this->productFacade->updateProductsUrl($productAbstractTransfers);

        $urlTransfersGroupedByAbstractId = [];
        foreach ($urlTransfers as $urlTransfer) {
            $urlTransfersGroupedByAbstractId[$urlTransfer->getFkResourceProductAbstract()][$urlTransfer->getFkLocale()] = $urlTransfer;
        }

        // Assert
        $this->assertArrayHasKey($firstProductAbstractTransfer->getIdProductAbstract(), $urlTransfersGroupedByAbstractId);
        $this->assertArrayHasKey($secondProductAbstractTransfer->getIdProductAbstract(), $urlTransfersGroupedByAbstractId);
        $this->assertCount(2, $urlTransfersGroupedByAbstractId[$secondProductAbstractTransfer->getIdProductAbstract()]);
        $this->assertCount(2, $urlTransfersGroupedByAbstractId[$firstProductAbstractTransfer->getIdProductAbstract()]);

        $firstProductAbstractUrlTransfers = $urlTransfersGroupedByAbstractId[$firstProductAbstractTransfer->getIdProductAbstract()];
        $this->assertSame(
            $firstProductAbstractUrlTransfers[$this->locales[static::EN_LOCALE]->getIdLocale()]->getUrl(),
            '/en-us/product-name-enus-' . $firstProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $firstProductAbstractUrlTransfers[$this->locales[static::EN_LOCALE]->getIdLocale()]->getFkResourceProductAbstract(),
            $firstProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $firstProductAbstractUrlTransfers[$this->locales[static::DE_LOCALE]->getIdLocale()]->getUrl(),
            '/de-de/product-name-dede-' . $firstProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $firstProductAbstractUrlTransfers[$this->locales[static::DE_LOCALE]->getIdLocale()]->getFkResourceProductAbstract(),
            $firstProductAbstractTransfer->getIdProductAbstract(),
        );

        $secondProductAbstractUrlTransfers = $urlTransfersGroupedByAbstractId[$secondProductAbstractTransfer->getIdProductAbstract()];
        $this->assertSame(
            $secondProductAbstractUrlTransfers[$this->locales[static::EN_LOCALE]->getIdLocale()]->getUrl(),
            '/en-us/product-name-enus-' . $secondProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $secondProductAbstractUrlTransfers[$this->locales[static::EN_LOCALE]->getIdLocale()]->getFkResourceProductAbstract(),
            $secondProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $secondProductAbstractUrlTransfers[$this->locales[static::DE_LOCALE]->getIdLocale()]->getUrl(),
            '/de-de/product-name-dede-' . $secondProductAbstractTransfer->getIdProductAbstract(),
        );
        $this->assertSame(
            $secondProductAbstractUrlTransfers[$this->locales[static::DE_LOCALE]->getIdLocale()]->getFkResourceProductAbstract(),
            $secondProductAbstractTransfer->getIdProductAbstract(),
        );
    }

    public function testUpdateProductUrlShouldSaveUrlForProductAbstract(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en-us/new-product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de-de/new-product-name-dede-' . $idProductAbstract);

        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName('New ' . $localizedAttribute->getName());
        }

        $productUrl = $this->productFacade->updateProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrl, $expectedENUrl);
        $this->assertProductUrl($productUrl, $expectedDEUrl);
    }

    public function testDeleteProductUrlShouldDeleteUrlForProductAbstract(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->createProductUrl($this->productAbstractTransfer);
        $this->productFacade->deleteProductUrl($this->productAbstractTransfer);

        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);
        $this->assertGreaterThan(0, count($productUrlTransfer->getUrls()));

        foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
            $this->assertNull($localizedUrlTransfer->getUrl());
        }
    }

    public function testCreateUrlShouldThrowExceptionWhenUrlExists(): void
    {
        $this->expectException(UrlExistsException::class);

        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->createProductUrl($this->productAbstractTransfer);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);
    }

    public function testUpdateUrlShouldNotThrowExceptionWhenUrlExistsForSameProduct(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->createProductUrl($this->productAbstractTransfer);
        $this->productFacade->updateProductUrl($this->productAbstractTransfer);
    }

    public function testProductUrlShouldBeUnique(): void
    {
        $this->expectException(UrlExistsException::class);

        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->updateProductUrl($this->productAbstractTransfer);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);
    }

    public function testDeleteProductUrlCanBeExecutedWhenUrlDoesNotExist(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        $this->productFacade->deleteProductUrl($this->productAbstractTransfer);
    }

    public function testGetProductUrl(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $expectedENUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en-us/product-name-enus-' . $idProductAbstract);
        $expectedDEUrl = (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de-de/product-name-dede-' . $idProductAbstract);

        $productUrl = $this->productFacade->getProductUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
        $this->assertProductUrl($productUrl, $expectedENUrl);
        $this->assertProductUrl($productUrl, $expectedDEUrl);
    }

    public function testTouchProductUrlActiveShouldTouchLogic(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productUrlManager->createProductUrl($this->productAbstractTransfer);

        $this->productFacade->touchProductAbstractUrlActive($this->productAbstractTransfer);

        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);
        $this->assertGreaterThan(0, count($productUrlTransfer->getUrls()));

        foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->setUrl($localizedUrlTransfer->getUrl());
            $urlTransfer = $this->urlFacade->findUrlCaseInsensitive($urlTransfer);

            $this->tester->assertTouchActive(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl());
        }
    }

    public function testTouchProductUrlDeletedShouldTouchLogic(): void
    {
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        $this->productFacade->touchProductAbstractUrlDeleted($this->productAbstractTransfer);

        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);
        $this->assertGreaterThan(0, count($productUrlTransfer->getUrls()));

        foreach ($productUrlTransfer->getUrls() as $localizedUrlTransfer) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->setUrl($localizedUrlTransfer->getUrl());
            $urlTransfer = $this->urlFacade->findUrlCaseInsensitive($urlTransfer);

            $this->tester->assertTouchDeleted(UrlConfig::RESOURCE_TYPE_URL, $urlTransfer->getIdUrl());
        }
    }

    public function testUpdateProductUrlSkipsUpdateWhenSlugIsUnchanged(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        // Act: update without changing the product name — slug is identical
        $this->productFacade->updateProductUrl($this->productAbstractTransfer);

        // Assert: stored URLs are unchanged because the slug did not change
        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en-us/product-name-enus-' . $idProductAbstract));
        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de-de/product-name-dede-' . $idProductAbstract));
    }

    public function testUpdateProductUrlCreatesUrlWhenNoExistingUrlPresent(): void
    {
        // Arrange: product exists but no URL has been created yet
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);

        // Act: update without a prior createProductUrl call
        $this->productFacade->updateProductUrl($this->productAbstractTransfer);

        // Assert: URL was created via the update path
        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en-us/product-name-enus-' . $idProductAbstract));
        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de-de/product-name-dede-' . $idProductAbstract));
    }

    public function testUpdateProductUrlPreservesUrlWhenOnlyLocalePrefixDiffers(): void
    {
        // Arrange: create URL with short locale prefix format (/de/slug, /en/slug)
        $shortLocaleFactory = new ProductBusinessFactory();
        $shortLocaleConfig = $this->createMock(ProductConfig::class);
        $shortLocaleConfig->method('isFullLocaleNamesInUrlEnabled')->willReturn(false);
        $shortLocaleFactory->setConfig($shortLocaleConfig);
        $this->productFacade->setFactory($shortLocaleFactory);

        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        // Act: switch to full locale prefix format (/de-de/slug) and update
        $fullLocaleFactory = new ProductBusinessFactory();
        $fullLocaleConfig = $this->createMock(ProductConfig::class);
        $fullLocaleConfig->method('isFullLocaleNamesInUrlEnabled')->willReturn(true);
        $fullLocaleFactory->setConfig($fullLocaleConfig);
        $this->productFacade->setFactory($fullLocaleFactory);

        $this->productFacade->updateProductUrl($this->productAbstractTransfer);

        // Assert: original short-prefix URL is preserved because the slug segment is identical
        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de/product-name-dede-' . $idProductAbstract));
        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en/product-name-enus-' . $idProductAbstract));
    }

    public function testUpdateProductUrlSavesNewUrlAndCapturesOldUrlWhenSlugChanges(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localizedAttribute->setName('New ' . $localizedAttribute->getName());
        }

        // Act
        $this->productFacade->updateProductUrl($this->productAbstractTransfer);

        // Assert: new URLs are stored
        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en-us/new-product-name-enus-' . $idProductAbstract));
        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de-de/new-product-name-dede-' . $idProductAbstract));

        // Assert: old URL is preserved as a redirect entry so existing links keep working
        $oldDeUrlTransfer = $this->urlFacade->findUrlCaseInsensitive(
            (new UrlTransfer())->setUrl('/de-de/product-name-dede-' . $idProductAbstract),
        );
        $this->assertNotNull($oldDeUrlTransfer, 'Old URL should remain as a redirect after the slug changes.');
    }

    public function testUpdateProductUrlOnlyUpdatesLocalesWhoseSlugChanged(): void
    {
        // Arrange
        $idProductAbstract = $this->productAbstractManager->createProductAbstract($this->productAbstractTransfer);
        $this->productAbstractTransfer->setIdProductAbstract($idProductAbstract);
        $this->productFacade->createProductUrl($this->productAbstractTransfer);

        // Change name only for DE locale
        foreach ($this->productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            if ($localizedAttribute->getLocale()->getLocaleName() === static::DE_LOCALE) {
                $localizedAttribute->setName('New ' . $localizedAttribute->getName());
            }
        }

        // Act
        $this->productFacade->updateProductUrl($this->productAbstractTransfer);

        // Assert: DE URL is updated to the new slug
        $productUrlTransfer = $this->productFacade->getProductUrl($this->productAbstractTransfer);

        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['de_DE'])
            ->setUrl('/de-de/new-product-name-dede-' . $idProductAbstract));

        // Assert: EN URL is unchanged because its slug did not change
        $this->assertProductUrl($productUrlTransfer, (new LocalizedUrlTransfer())
            ->setLocale($this->locales['en_US'])
            ->setUrl('/en-us/product-name-enus-' . $idProductAbstract));
    }

    protected function assertProductUrl(ProductUrlTransfer $productUrl, LocalizedUrlTransfer $expectedUrl): void
    {
        $this->assertSame($productUrl->getAbstractSku(), $productUrl->getAbstractSku());

        $urls = [];
        foreach ($productUrl->getUrls() as $actualUrlTransfer) {
            $urls[$actualUrlTransfer->getLocale()->getLocaleName()] = $actualUrlTransfer->getUrl();
        }

        $this->assertArrayHasKey($expectedUrl->getLocale()->getLocaleName(), $urls);
        $this->assertSame($expectedUrl->getUrl(), $urls[$expectedUrl->getLocale()->getLocaleName()]);
    }
}
