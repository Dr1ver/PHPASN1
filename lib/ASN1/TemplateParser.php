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

use Exception;
use FG\ASN1\Exception\ParserException;
use FG\ASN1\Universal\Sequence;

class TemplateParser
{
    /**
     * @param string $data
     * @param array $template
     * @return \FG\ASN1\Object|Sequence
     * @throws ParserException if there was an issue parsing
     */
    public function parseBase64($data, array $template)
    {
        // TODO test with invalid data
        return $this->parseBinary(base64_decode($data), $template);
    }

    /**
     * @param string $binary
     * @param array $template
     * @return \FG\ASN1\Object|Sequence
     * @throws ParserException if there was an issue parsing
     */
    public function parseBinary($binary, array $template)
    {
        $parsedObject = Object::fromBinary($binary);

        foreach ($template as $key => $value) {
            $this->validate($parsedObject, $key, $value);
        }

        return $parsedObject;
    }

    private function validate(Object $object, $key, $value)
    {
        if (is_array($value)) {
            $this->assertTypeId($key, $object);

            /* @var Construct $object */
            $childrenCount = count($value);
            reset($value);
            for ($i = 0; $i < $childrenCount; $i++) {
                $this->validate($object->getChildren()[$i], key($value), current($value));
                next($value);
            }
        } else {
            $this->assertTypeId($value, $object);
        }
    }

    private function assertTypeId($expectedTypeId, Object $object)
    {
        $actualType = $object->getIdentifier()->getTagNumber();
        if ($expectedTypeId != $actualType) {
            throw new Exception("Expected type ($expectedTypeId) does not match actual type ($actualType");
        }
    }
}
