<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Attribute;

class AttributeEncoder implements AttributeEncoderInterface
{
    /**
     * @param array $attributes
     *
     * @return string
     */
    public function encodeAttributes(array $attributes)
    {
        return (string)json_encode($attributes);
    }

    /**
     * @param string $json
     *
     * @return array
     */
    public function decodeAttributes($json)
    {
        $value = json_decode($json, true);

        if (!is_array($value)) {
            $value = [];
        }

        return $value;
    }
}
