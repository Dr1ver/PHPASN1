<?php
/*
 * This file is part of the PHPASN1 library.
 *
 * Copyright © Friedrich Große <friedrich.grosse@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FG\ASN1;

use FG\ASN1\Universal\EOC;

class UnknownConstructedObject extends Construct
{
    protected function getEncodedValue()
    {
        return '';
    }
}
