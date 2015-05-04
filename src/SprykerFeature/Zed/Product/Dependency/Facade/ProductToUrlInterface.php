<?php

namespace SprykerFeature\Zed\Product\Dependency\Facade;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use Generated\Shared\Transfer\UrlUrlTransfer;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

interface ProductToUrlInterface
{
    /**
     * @param string $url
     * @param LocaleDto $locale
     * @param string $resourceType
     * @param int $resourceId
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrl($url, LocaleDto $locale, $resourceType, $resourceId);

    /**
     * @param int $idUrl
     */
    public function touchUrlActive($idUrl);
}
