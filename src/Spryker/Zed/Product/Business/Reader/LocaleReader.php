<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Product\Business\Reader;

use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;

class LocaleReader implements LocaleReaderInterface
{
    public function __construct(
        protected readonly ProductToLocaleInterface $localeFacade,
    ) {
    }

    /**
     * @param array<string> $localeNames
     *
     * @return array<string>
     */
    public function getMissingLocaleNames(array $localeNames): array
    {
        if ($localeNames === []) {
            return [];
        }

        return array_values(array_diff($localeNames, $this->getInstalledLocaleNames()));
    }

    /**
     * @return array<string>
     */
    protected function getInstalledLocaleNames(): array
    {
        $installedLocaleNames = [];

        foreach ($this->localeFacade->getLocaleCollection() as $localeTransfer) {
            $localeName = $localeTransfer->getLocaleName();

            if ($localeName !== null && $localeName !== '') {
                $installedLocaleNames[] = $localeName;
            }
        }

        return $installedLocaleNames;
    }
}
