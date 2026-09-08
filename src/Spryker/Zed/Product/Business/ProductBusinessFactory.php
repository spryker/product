<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoder;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface;
use Spryker\Zed\Product\Business\Attribute\AttributeKeyManager;
use Spryker\Zed\Product\Business\Attribute\AttributeLoader;
use Spryker\Zed\Product\Business\Attribute\AttributeMerger;
use Spryker\Zed\Product\Business\Exporter\ProductEventBusExporter;
use Spryker\Zed\Product\Business\Exporter\ProductExporterInterface;
use Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertion;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertion;
use Spryker\Zed\Product\Business\Product\LocalizedAttributesLocaleResolver;
use Spryker\Zed\Product\Business\Product\LocalizedAttributesLocaleResolverInterface;
use Spryker\Zed\Product\Business\Product\Mapper\ProductAttributeMapper;
use Spryker\Zed\Product\Business\Product\Mapper\ProductAttributeMapperInterface;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductAbstractLocalizedAttributesDataMerger;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductAttributesDataMerger;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductLocalizedAttributesDataMerger;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductSearchMetadataMerger;
use Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductStoreDataMerger;
use Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMerger;
use Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMergerInterface;
use Spryker\Zed\Product\Business\Product\NameGenerator\ProductAbstractNameGenerator;
use Spryker\Zed\Product\Business\Product\NameGenerator\ProductConcreteNameGenerator;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractAfterCreateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractAfterUpdateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractBeforeCreateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractBeforeUpdateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductAbstractReadObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteAfterCreateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteAfterUpdateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteBeforeCreateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteBeforeUpdateObserverPluginManager;
use Spryker\Zed\Product\Business\Product\Plugin\ProductConcreteReadObserverPluginManager;
use Spryker\Zed\Product\Business\Product\ProductAbstractManager;
use Spryker\Zed\Product\Business\Product\ProductConcreteActivator;
use Spryker\Zed\Product\Business\Product\ProductConcreteManager;
use Spryker\Zed\Product\Business\Product\ProductManager;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGenerator;
use Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGeneratorInterface;
use Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusChecker;
use Spryker\Zed\Product\Business\Product\Status\ProductConcreteStatusChecker;
use Spryker\Zed\Product\Business\Product\Status\ProductConcreteStatusCheckerInterface;
use Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReader;
use Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationWriter;
use Spryker\Zed\Product\Business\Product\Suggest\ProductSuggester;
use Spryker\Zed\Product\Business\Product\Suggest\ProductSuggesterInterface;
use Spryker\Zed\Product\Business\Product\Touch\ProductAbstractTouch;
use Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouch;
use Spryker\Zed\Product\Business\Product\Trigger\ProductEventTrigger;
use Spryker\Zed\Product\Business\Product\Trigger\ProductEventTriggerInterface;
use Spryker\Zed\Product\Business\Product\Url\ProductAbstractAfterUpdateUrlObserver;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlGenerator;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManager;
use Spryker\Zed\Product\Business\Product\Validator\ProductAbstractCollectionCreateValidator;
use Spryker\Zed\Product\Business\Product\Validator\ProductAbstractCollectionUpdateValidator;
use Spryker\Zed\Product\Business\Product\Validator\ProductAbstractCollectionValidatorInterface;
use Spryker\Zed\Product\Business\Product\Validator\ProductConcreteCollectionCreateValidator;
use Spryker\Zed\Product\Business\Product\Validator\ProductConcreteCollectionUpdateValidator;
use Spryker\Zed\Product\Business\Product\Validator\ProductConcreteCollectionValidatorInterface;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractCollectionValidatorRuleInterface;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractCreateValidatorRule;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractLocaleValidatorRule;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractStoreValidatorRule;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractUpdateValidatorRule;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductConcreteCollectionValidatorRuleInterface;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductConcreteCreateValidatorRule;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductConcreteLocaleValidatorRule;
use Spryker\Zed\Product\Business\Product\Validator\Rule\ProductConcreteUpdateValidatorRule;
use Spryker\Zed\Product\Business\Product\Variant\AttributePermutationGenerator;
use Spryker\Zed\Product\Business\Product\Variant\VariantGenerator;
use Spryker\Zed\Product\Business\Provider\ProductReadiness\IsActiveAbstractProductReadinessProvider;
use Spryker\Zed\Product\Business\Provider\ProductReadiness\IsActiveConcreteProductReadinessProvider;
use Spryker\Zed\Product\Business\Provider\ProductReadiness\ProductAbstractReadinessProviderInterface;
use Spryker\Zed\Product\Business\Provider\ProductReadiness\ProductConcreteReadinessProviderInterface;
use Spryker\Zed\Product\Business\Provider\ProductReadiness\StoreRelationAbstractProductReadinessProvider;
use Spryker\Zed\Product\Business\Publisher\ProductMessageBrokerPublisher;
use Spryker\Zed\Product\Business\Publisher\ProductPublisherInterface;
use Spryker\Zed\Product\Business\Reader\LocaleReader;
use Spryker\Zed\Product\Business\Reader\LocaleReaderInterface;
use Spryker\Zed\Product\Business\Reader\ProductAbstractReader;
use Spryker\Zed\Product\Business\Reader\ProductAbstractReaderInterface;
use Spryker\Zed\Product\Business\Reader\ProductAbstractSkuReader;
use Spryker\Zed\Product\Business\Reader\ProductAbstractSkuReaderInterface;
use Spryker\Zed\Product\Business\Reader\ProductConcreteReader;
use Spryker\Zed\Product\Business\Reader\ProductConcreteReaderInterface;
use Spryker\Zed\Product\Business\Reader\StoreReader;
use Spryker\Zed\Product\Business\Reader\StoreReaderInterface;
use Spryker\Zed\Product\Business\Transfer\ProductTransferMapper;
use Spryker\Zed\Product\Business\Writer\ProductAbstractCollectionCreator;
use Spryker\Zed\Product\Business\Writer\ProductAbstractCollectionCreatorInterface;
use Spryker\Zed\Product\Business\Writer\ProductAbstractCollectionUpdater;
use Spryker\Zed\Product\Business\Writer\ProductAbstractCollectionUpdaterInterface;
use Spryker\Zed\Product\Business\Writer\ProductConcreteCollectionCreator;
use Spryker\Zed\Product\Business\Writer\ProductConcreteCollectionCreatorInterface;
use Spryker\Zed\Product\Business\Writer\ProductConcreteCollectionUpdater;
use Spryker\Zed\Product\Business\Writer\ProductConcreteCollectionUpdaterInterface;
use Spryker\Zed\Product\Business\Writer\ProductConcreteWriter;
use Spryker\Zed\Product\Business\Writer\ProductConcreteWriterInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToMessageBrokerInterface;
use Spryker\Zed\Product\ProductDependencyProvider;

/**
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Product\Persistence\ProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\Product\Persistence\ProductEntityManagerInterface getEntityManager()
 */
class ProductBusinessFactory extends AbstractBusinessFactory
{
    protected static ?AttributeEncoderInterface $attributeEncoder = null;

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductManagerInterface
     */
    public function createProductManager()
    {
        return new ProductManager(
            $this->createProductAbstractManager(),
            $this->createProductConcreteManager(),
            $this->getQueryContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    public function createProductAbstractManager()
    {
        $productAbstractManager = new ProductAbstractManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createProductAbstractAssertion(),
            $this->createSkuGenerator(),
            $this->createAttributeEncoder(),
            $this->createProductTransferMapper(),
            $this->createProductAbstractStoreRelationReader(),
            $this->createProductAbstractStoreRelationWriter(),
            $this->getProductAbstractPreCreatePlugins(),
            $this->createProductEventTrigger(),
            $this->getRepository(),
            $this->createProductAttributesMapper(),
            $this->createLocalizedAttributesLocaleResolver(),
        );

        $productAbstractManager->setEventFacade($this->getEventFacade());
        $productAbstractManager->attachBeforeCreateObserver($this->createProductAbstractBeforeCreateObserverPluginManager());
        $productAbstractManager->attachAfterCreateObserver($this->createProductAbstractAfterCreateObserverPluginManager());
        $productAbstractManager->attachBeforeUpdateObserver($this->createProductAbstractBeforeUpdateObserverPluginManager());
        $productAbstractManager->attachAfterUpdateObserver($this->createProductAbstractAfterUpdateObserverPluginManager());
        $productAbstractManager->attachAfterUpdateObserver($this->createProductAbstractAfterUpdateUrlObserver());
        $productAbstractManager->attachReadObserver($this->createProductAbstractReadObserverPluginManager());

        return $productAbstractManager;
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    public function createProductConcreteManager()
    {
        $productConcreteManager = new ProductConcreteManager(
            $this->getQueryContainer(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->createProductAbstractAssertion(),
            $this->createProductConcreteAssertion(),
            $this->createAttributeEncoder(),
            $this->createProductTransferMapper(),
            $this->getRepository(),
            $this->getProductConcreteExpanderPlugins(),
            $this->createProductEventTrigger(),
            $this->createLocalizedAttributesLocaleResolver(),
        );

        $productConcreteManager->setEventFacade($this->getEventFacade());
        $productConcreteManager->attachBeforeCreateObserver($this->createProductConcreteBeforeCreateObserverPluginManager());
        $productConcreteManager->attachAfterCreateObserver($this->createProductConcreteAfterCreateObserverPluginManager());
        $productConcreteManager->attachBeforeUpdateObserver($this->createProductConcreteBeforeUpdateObserverPluginManager());
        $productConcreteManager->attachAfterUpdateObserver($this->createProductConcreteAfterUpdateObserverPluginManager());
        $productConcreteManager->attachReadObserver($this->createProductConcreteReadObserverPluginManager());

        return $productConcreteManager;
    }

    public function createLocalizedAttributesLocaleResolver(): LocalizedAttributesLocaleResolverInterface
    {
        return new LocalizedAttributesLocaleResolver(
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\ProductConcreteActivatorInterface
     */
    public function createProductConcreteActivator()
    {
        return new ProductConcreteActivator(
            $this->createProductAbstractStatusChecker(),
            $this->createProductAbstractManager(),
            $this->createProductConcreteManager(),
            $this->createProductUrlManager(),
            $this->createProductConcreteTouch(),
            $this->getQueryContainer(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface
     */
    public function createProductUrlManager()
    {
        return new ProductUrlManager(
            $this->getUrlFacade(),
            $this->getTouchFacade(),
            $this->getLocaleFacade(),
            $this->getQueryContainer(),
            $this->createProductUrlGenerator(),
            $this->createProductEventTrigger(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface
     */
    public function createProductUrlGenerator()
    {
        return new ProductUrlGenerator(
            $this->createProductAbstractNameGenerator(),
            $this->getLocaleFacade(),
            $this->getUtilTextService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\VariantGeneratorInterface
     */
    public function createProductVariantGenerator()
    {
        return new VariantGenerator(
            $this->getUrlFacade(),
            $this->createSkuGenerator(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface
     */
    public function createProductAbstractStatusChecker()
    {
        return new ProductAbstractStatusChecker($this->getQueryContainer(), $this->getRepository());
    }

    public function createProductConcreteStatusChecker(): ProductConcreteStatusCheckerInterface
    {
        return new ProductConcreteStatusChecker($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface
     */
    public function createSkuGenerator()
    {
        return new SkuGenerator($this->getUtilTextService(), $this->createSkuIncrementGenerator());
    }

    public function createSkuIncrementGenerator(): SkuIncrementGeneratorInterface
    {
        return new SkuIncrementGenerator($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\AttributePermutationGeneratorInterface
     */
    public function createAttributePermutationGenerator()
    {
        return new AttributePermutationGenerator();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeKeyManagerInterface
     */
    public function createAttributeKeyManager()
    {
        return new AttributeKeyManager($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    public function createAttributeEncoder()
    {
        return static::$attributeEncoder ??= new AttributeEncoder();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Transfer\ProductTransferMapperInterface
     */
    public function createProductTransferMapper()
    {
        return new ProductTransferMapper($this->createAttributeEncoder());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeMergerInterface
     */
    public function createAttributeMerger()
    {
        return new AttributeMerger();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Attribute\AttributeLoaderInterface
     */
    public function createAttributeLoader()
    {
        return new AttributeLoader(
            $this->getQueryContainer(),
            $this->createAttributeMerger(),
            $this->createAttributeEncoder(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Touch\ProductAbstractTouchInterface
     */
    public function createProductAbstractTouch()
    {
        return new ProductAbstractTouch(
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->createProductAbstractStatusChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface
     */
    public function createProductConcreteTouch()
    {
        return new ProductConcreteTouch(
            $this->getTouchFacade(),
            $this->getQueryContainer(),
            $this->createProductAbstractStatusChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\NameGenerator\ProductAbstractNameGeneratorInterface
     */
    public function createProductAbstractNameGenerator()
    {
        return new ProductAbstractNameGenerator();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\NameGenerator\ProductConcreteNameGeneratorInterface
     */
    public function createProductConcreteNameGenerator()
    {
        return new ProductConcreteNameGenerator();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationReaderInterface
     */
    public function createProductAbstractStoreRelationReader()
    {
        return new ProductAbstractStoreRelationReader(
            $this->getQueryContainer(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\StoreRelation\ProductAbstractStoreRelationWriterInterface
     */
    public function createProductAbstractStoreRelationWriter()
    {
        return new ProductAbstractStoreRelationWriter(
            $this->getQueryContainer(),
            $this->createProductAbstractStoreRelationReader(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface
     */
    protected function getStoreFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected function getUrlFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface
     */
    protected function getUtilTextService()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Service\ProductToUtilEncodingInterface
     */
    protected function getUtilEncodingService()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Assertion\ProductAbstractAssertionInterface
     */
    protected function createProductAbstractAssertion()
    {
        return new ProductAbstractAssertion(
            $this->getQueryContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface
     */
    protected function createProductConcreteAssertion()
    {
        return new ProductConcreteAssertion(
            $this->getQueryContainer(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface>
     */
    protected function getProductAbstractBeforeCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_BEFORE_CREATE);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\Business\ProductBusinessFactory::getProductAbstractPostCreatePlugins()} instead.
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginCreateInterface>
     */
    protected function getProductAbstractAfterCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPostCreatePluginInterface>
     */
    public function getProductAbstractPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface>
     */
    public function getProductConcreteExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_CONCRETE_EXPANDER);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\Business\ProductBusinessFactory::getProductAbstractExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface>
     */
    protected function getProductAbstractReadPlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_READ);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractExpanderPluginInterface>
     */
    public function getProductAbstractExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface>
     */
    protected function getProductAbstractBeforeUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_BEFORE_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface>
     */
    protected function getProductAbstractAfterUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_ABSTRACT_PLUGINS_AFTER_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCreatePluginInterface>
     */
    protected function getProductConcreteBeforeCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_BEFORE_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCreatePluginInterface>
     */
    protected function getProductConcreteAfterCreatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_AFTER_CREATE);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Product\Business\ProductBusinessFactory::getProductConcreteExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface>
     */
    protected function getProductConcreteReadPlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_READ);
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface>
     */
    protected function getProductConcreteBeforeUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_BEFORE_UPDATE);
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface>
     */
    protected function getProductConcreteAfterUpdatePlugins()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PRODUCT_CONCRETE_PLUGINS_AFTER_UPDATE);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface
     */
    protected function createProductAbstractBeforeCreateObserverPluginManager()
    {
        return new ProductAbstractBeforeCreateObserverPluginManager(
            $this->getProductAbstractBeforeCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface
     */
    protected function createProductAbstractAfterCreateObserverPluginManager()
    {
        return new ProductAbstractAfterCreateObserverPluginManager(
            $this->getProductAbstractAfterCreatePlugins(),
            $this->getProductAbstractPostCreatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface
     */
    protected function createProductAbstractBeforeUpdateObserverPluginManager()
    {
        return new ProductAbstractBeforeUpdateObserverPluginManager($this->getProductAbstractBeforeUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface
     */
    protected function createProductAbstractAfterUpdateObserverPluginManager()
    {
        return new ProductAbstractAfterUpdateObserverPluginManager($this->getProductAbstractAfterUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface
     */
    protected function createProductAbstractAfterUpdateUrlObserver()
    {
        return new ProductAbstractAfterUpdateUrlObserver($this->createProductAbstractStatusChecker(), $this->createProductUrlManager());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractReadObserverInterface
     */
    protected function createProductAbstractReadObserverPluginManager()
    {
        return new ProductAbstractReadObserverPluginManager(
            $this->getProductAbstractReadPlugins(),
            $this->getProductAbstractExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteCreateObserverInterface
     */
    protected function createProductConcreteBeforeCreateObserverPluginManager()
    {
        return new ProductConcreteBeforeCreateObserverPluginManager($this->getProductConcreteBeforeCreatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteCreateObserverInterface
     */
    protected function createProductConcreteAfterCreateObserverPluginManager()
    {
        return new ProductConcreteAfterCreateObserverPluginManager($this->getProductConcreteAfterCreatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface
     */
    protected function createProductConcreteBeforeUpdateObserverPluginManager()
    {
        return new ProductConcreteBeforeUpdateObserverPluginManager($this->getProductConcreteBeforeUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface
     */
    protected function createProductConcreteAfterUpdateObserverPluginManager()
    {
        return new ProductConcreteAfterUpdateObserverPluginManager($this->getProductConcreteAfterUpdatePlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteReadObserverInterface
     */
    protected function createProductConcreteReadObserverPluginManager()
    {
        return new ProductConcreteReadObserverPluginManager($this->getProductConcreteReadPlugins());
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface
     */
    protected function getEventFacade()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_EVENT);
    }

    public function createProductSuggester(): ProductSuggesterInterface
    {
        return new ProductSuggester(
            $this->getConfig(),
            $this->getRepository(),
            $this->getLocaleFacade(),
        );
    }

    public function createProductConcreteWriter(): ProductConcreteWriterInterface
    {
        return new ProductConcreteWriter(
            $this->createProductConcreteManager(),
            $this->getEntityManager(),
            $this->createProductConcreteAssertion(),
            $this->createLocalizedAttributesLocaleResolver(),
        );
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface>
     */
    public function getProductConcreteBeforeUpdateCollectionPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_CONCRETE_BEFORE_UPDATE_COLLECTION);
    }

    /**
     * @return array<\Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface>
     */
    public function getProductConcreteAfterUpdateCollectionPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_CONCRETE_AFTER_UPDATE_COLLECTION);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPreCreatePluginInterface>
     */
    public function getProductAbstractPreCreatePlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_PRE_CREATE);
    }

    public function createProductConcreteCollectionCreator(): ProductConcreteCollectionCreatorInterface
    {
        return new ProductConcreteCollectionCreator(
            $this->createProductConcreteCollectionCreateValidator(),
            $this->createProductConcreteManager(),
        );
    }

    public function createProductConcreteCollectionUpdater(): ProductConcreteCollectionUpdaterInterface
    {
        return new ProductConcreteCollectionUpdater(
            $this->createProductConcreteCollectionUpdateValidator(),
            $this->getEntityManager(),
            $this->createLocalizedAttributesLocaleResolver(),
            $this->getProductConcreteBeforeUpdateCollectionPlugins(),
            $this->getProductConcreteAfterUpdateCollectionPlugins(),
        );
    }

    public function createProductAbstractCollectionCreator(): ProductAbstractCollectionCreatorInterface
    {
        return new ProductAbstractCollectionCreator(
            $this->createProductAbstractCollectionCreateValidator(),
            $this->createProductAbstractManager(),
        );
    }

    public function createProductAbstractCollectionUpdater(): ProductAbstractCollectionUpdaterInterface
    {
        return new ProductAbstractCollectionUpdater(
            $this->createProductAbstractCollectionUpdateValidator(),
            $this->createProductAbstractManager(),
        );
    }

    public function createProductConcreteCollectionCreateValidator(): ProductConcreteCollectionValidatorInterface
    {
        return new ProductConcreteCollectionCreateValidator(
            $this->getProductConcreteCollectionCreateValidatorRules(),
            $this->getProductConcreteCollectionCreateValidatorPlugins(),
        );
    }

    public function createProductConcreteCollectionUpdateValidator(): ProductConcreteCollectionValidatorInterface
    {
        return new ProductConcreteCollectionUpdateValidator(
            $this->getProductConcreteCollectionUpdateValidatorRules(),
            $this->getProductConcreteCollectionUpdateValidatorPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\Product\Business\Product\Validator\Rule\ProductConcreteCollectionValidatorRuleInterface>
     */
    public function getProductConcreteCollectionCreateValidatorRules(): array
    {
        return [
            $this->createProductConcreteCreateValidatorRule(),
            $this->createProductConcreteLocaleValidatorRule(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\Product\Business\Product\Validator\Rule\ProductConcreteCollectionValidatorRuleInterface>
     */
    public function getProductConcreteCollectionUpdateValidatorRules(): array
    {
        return [
            $this->createProductConcreteUpdateValidatorRule(),
            $this->createProductConcreteLocaleValidatorRule(),
        ];
    }

    public function createProductAbstractCollectionCreateValidator(): ProductAbstractCollectionValidatorInterface
    {
        return new ProductAbstractCollectionCreateValidator(
            $this->getProductAbstractCollectionCreateValidatorRules(),
            $this->getProductAbstractCollectionCreateValidatorPlugins(),
        );
    }

    public function createProductAbstractCollectionUpdateValidator(): ProductAbstractCollectionValidatorInterface
    {
        return new ProductAbstractCollectionUpdateValidator(
            $this->getProductAbstractCollectionUpdateValidatorRules(),
            $this->getProductAbstractCollectionUpdateValidatorPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractCollectionValidatorRuleInterface>
     */
    public function getProductAbstractCollectionCreateValidatorRules(): array
    {
        return [
            $this->createProductAbstractCreateValidatorRule(),
            $this->createProductAbstractLocaleValidatorRule(),
            $this->createProductAbstractStoreValidatorRule(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\Product\Business\Product\Validator\Rule\ProductAbstractCollectionValidatorRuleInterface>
     */
    public function getProductAbstractCollectionUpdateValidatorRules(): array
    {
        return [
            $this->createProductAbstractUpdateValidatorRule(),
            $this->createProductAbstractLocaleValidatorRule(),
            $this->createProductAbstractStoreValidatorRule(),
        ];
    }

    public function createProductConcreteCreateValidatorRule(): ProductConcreteCollectionValidatorRuleInterface
    {
        return new ProductConcreteCreateValidatorRule(
            $this->createProductConcreteManager(),
            $this->createProductAbstractManager(),
        );
    }

    public function createProductConcreteUpdateValidatorRule(): ProductConcreteCollectionValidatorRuleInterface
    {
        return new ProductConcreteUpdateValidatorRule(
            $this->getRepository(),
            $this->createProductConcreteManager(),
        );
    }

    public function createProductAbstractCreateValidatorRule(): ProductAbstractCollectionValidatorRuleInterface
    {
        return new ProductAbstractCreateValidatorRule($this->createProductAbstractSkuReader());
    }

    public function createProductAbstractUpdateValidatorRule(): ProductAbstractCollectionValidatorRuleInterface
    {
        return new ProductAbstractUpdateValidatorRule($this->createProductAbstractSkuReader());
    }

    public function createProductAbstractSkuReader(): ProductAbstractSkuReaderInterface
    {
        return new ProductAbstractSkuReader($this->getRepository());
    }

    public function createProductConcreteLocaleValidatorRule(): ProductConcreteCollectionValidatorRuleInterface
    {
        return new ProductConcreteLocaleValidatorRule($this->createLocaleReader());
    }

    public function createProductAbstractLocaleValidatorRule(): ProductAbstractCollectionValidatorRuleInterface
    {
        return new ProductAbstractLocaleValidatorRule($this->createLocaleReader());
    }

    public function createProductAbstractStoreValidatorRule(): ProductAbstractCollectionValidatorRuleInterface
    {
        return new ProductAbstractStoreValidatorRule($this->createStoreReader());
    }

    public function createStoreReader(): StoreReaderInterface
    {
        return new StoreReader($this->getStoreFacade());
    }

    public function createLocaleReader(): LocaleReaderInterface
    {
        return new LocaleReader($this->getLocaleFacade());
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCollectionCreateValidatorPluginInterface>
     */
    public function getProductConcreteCollectionCreateValidatorPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_CONCRETE_COLLECTION_CREATE_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteCollectionUpdateValidatorPluginInterface>
     */
    public function getProductConcreteCollectionUpdateValidatorPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_CONCRETE_COLLECTION_UPDATE_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionCreateValidatorPluginInterface>
     */
    public function getProductAbstractCollectionCreateValidatorPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_COLLECTION_CREATE_VALIDATOR);
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionUpdateValidatorPluginInterface>
     */
    public function getProductAbstractCollectionUpdateValidatorPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_COLLECTION_UPDATE_VALIDATOR);
    }

    public function createProductEventBusExporter(): ProductExporterInterface
    {
        return new ProductEventBusExporter(
            $this->getEventFacade(),
            $this->getStoreFacade(),
            $this->createProductConcreteManager(),
            $this->getConfig(),
        );
    }

    public function createProductConcreteReader(): ProductConcreteReaderInterface
    {
        return new ProductConcreteReader(
            $this->createProductConcreteManager(),
            $this->createProductAbstractManager(),
            $this->createProductUrlManager(),
            $this->createProductConcreteMerger(),
            $this->getRepository(),
            $this->getProductConcreteExpanderPlugins(),
        );
    }

    public function createProductMessageBrokerPublisher(): ProductPublisherInterface
    {
        return new ProductMessageBrokerPublisher(
            $this->createProductConcreteReader(),
            $this->getMessageBrokerFacade(),
            $this->getRepository(),
            $this->getConfig(),
            $this->createProductEventTrigger(),
        );
    }

    public function getMessageBrokerFacade(): ProductToMessageBrokerInterface
    {
        return $this->getProvidedDependency(ProductDependencyProvider::FACADE_MESSAGE_BROKER);
    }

    public function createProductConcreteMerger(): ProductConcreteMergerInterface
    {
        return new ProductConcreteMerger(
            $this->getProductDataMergers(),
            $this->getProductConcreteMergerPlugins(),
        );
    }

    public function createProductEventTrigger(): ProductEventTriggerInterface
    {
        return new ProductEventTrigger(
            $this->getEventFacade(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteMergerPluginInterface>
     */
    public function getProductConcreteMergerPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_CONCRETE_MERGER);
    }

    public function createProductAbstractReader(): ProductAbstractReaderInterface
    {
        return new ProductAbstractReader(
            $this->getRepository(),
            $this->getProductAbstractCollectionPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionExpanderPluginInterface>
     */
    public function getProductAbstractCollectionPlugins(): array
    {
        return $this->getProvidedDependency(ProductDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_COLLECTION_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface>
     */
    public function getProductDataMergers(): array
    {
        return [
            $this->createProductAbstractLocalizedAttributesDataMerger(),
            $this->createProductAttributesDataMerger(),
            $this->createProductLocalizedAttributesDataMerger(),
            $this->createProductSearchMetadataMerger(),
            $this->createProductStoreDataMerger(),
        ];
    }

    public function createProductAbstractLocalizedAttributesDataMerger(): ProductDataMergerInterface
    {
        return new ProductAbstractLocalizedAttributesDataMerger();
    }

    public function createProductAttributesDataMerger(): ProductDataMergerInterface
    {
        return new ProductAttributesDataMerger();
    }

    public function createProductLocalizedAttributesDataMerger(): ProductDataMergerInterface
    {
        return new ProductLocalizedAttributesDataMerger();
    }

    public function createProductSearchMetadataMerger(): ProductDataMergerInterface
    {
        return new ProductSearchMetadataMerger();
    }

    public function createProductStoreDataMerger(): ProductDataMergerInterface
    {
        return new ProductStoreDataMerger();
    }

    public function createProductAttributesMapper(): ProductAttributeMapperInterface
    {
        return new ProductAttributeMapper(
            $this->createAttributeEncoder(),
        );
    }

    public function createIsActiveAbstractProductReadinessProvider(): ProductAbstractReadinessProviderInterface
    {
        return new IsActiveAbstractProductReadinessProvider();
    }

    public function createIsActiveConcreteProductReadinessProvider(): ProductConcreteReadinessProviderInterface
    {
        return new IsActiveConcreteProductReadinessProvider();
    }

    public function createStoreRelationAbstractProductReadinessProvider(): ProductAbstractReadinessProviderInterface
    {
        return new StoreRelationAbstractProductReadinessProvider(
            $this->getStoreFacade(),
        );
    }
}
