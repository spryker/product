<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use ArrayObject;
use Generated\Shared\Transfer\LocaleConditionsTransfer;
use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;

class LocalizedAttributesLocaleResolver implements LocalizedAttributesLocaleResolverInterface
{
    public function __construct(
        protected ProductToLocaleInterface $localeFacade,
    ) {
    }

    public function resolveLocales(ArrayObject $localizedAttributesTransfers): ArrayObject
    {
        $localeTransfersIndexedByName = $this->resolveLocalesByName($localizedAttributesTransfers);

        foreach ($localizedAttributesTransfers as $localizedAttributesTransfer) {
            $localizedAttributesTransfer->setLocale(
                $this->getResolvedLocale($localizedAttributesTransfer->getLocale(), $localeTransfersIndexedByName),
            );
        }

        return $localizedAttributesTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\LocaleTransfer>
     */
    protected function resolveLocalesByName(ArrayObject $localizedAttributesTransfers): array
    {
        $localeNamesToResolve = [];

        foreach ($localizedAttributesTransfers as $localizedAttributesTransfer) {
            $locale = $localizedAttributesTransfer->getLocale();

            if ($locale !== null && $locale->getIdLocale() === null && $locale->getLocaleName() !== null) {
                $localeNamesToResolve[$locale->getLocaleName()] = true;
            }
        }

        if ($localeNamesToResolve === []) {
            return [];
        }

        $localeCriteriaTransfer = (new LocaleCriteriaTransfer())
            ->setLocaleConditions(
                (new LocaleConditionsTransfer())->setLocaleNames(array_keys($localeNamesToResolve)),
            );

        $localeTransfersIndexedByName = [];

        foreach ($this->localeFacade->getLocaleCollection($localeCriteriaTransfer) as $localeTransfer) {
            $localeTransfersIndexedByName[$localeTransfer->getLocaleNameOrFail()] = $localeTransfer;
        }

        return $localeTransfersIndexedByName;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfersIndexedByName
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     */
    protected function getResolvedLocale(?LocaleTransfer $localeTransfer, array $localeTransfersIndexedByName): LocaleTransfer
    {
        if ($localeTransfer === null) {
            throw new RequiredTransferPropertyException(
                sprintf('Missing required property "locale" for "%s".', LocaleTransfer::class),
            );
        }

        if ($localeTransfer->getIdLocale() !== null) {
            return $localeTransfer;
        }

        $localeName = $localeTransfer->getLocaleName();

        if ($localeName === null) {
            throw new RequiredTransferPropertyException(
                sprintf('Either "idLocale" or "localeName" must be set on "%s".', LocaleTransfer::class),
            );
        }

        if (!isset($localeTransfersIndexedByName[$localeName])) {
            throw new RequiredTransferPropertyException(
                sprintf('Locale with name "%s" not found.', $localeName),
            );
        }

        return $localeTransfersIndexedByName[$localeName];
    }
}
