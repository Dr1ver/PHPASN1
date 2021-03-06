<?php
/*
 * This file is part of the PHPASN1 library.
 *
 * Copyright © Friedrich Gro?e <friedrich.grosse@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FG\ASN1\Universal;

use FG\ASN1\ASN1Object;
use FG\ASN1\Identifier;

class EOC extends ASN1Object
{
    public function getType()
    {
        return Identifier::EOC;
    }

    protected function calculateContentLength()
    {
        return 0;
    }

    protected function getEncodedValue()
    {
        return null;
    }

    public function __toString(): string
    {
        return '';
    }
}
