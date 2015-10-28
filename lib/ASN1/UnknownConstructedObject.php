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
    private $identifier;
    private $contentLength;

    /**
     * @param string $binaryData
     * @param int $offsetIndex
     *
     * @throws \FG\ASN1\Exception\ParserException
     */
    public function __construct($binaryData, &$offsetIndex)
    {
        $startPos = $offsetIndex - 2;
        $this->identifier = self::parseBinaryIdentifier($binaryData, $offsetIndex);
        $contentLength = self::parseContentLength($binaryData, $offsetIndex);

        $children = [];
        $octetsToRead = $this->contentLength;
        while ($octetsToRead > 0) {
            $newChild = Object::fromBinary($binaryData, $offsetIndex);
            $octetsToRead -= $newChild->getObjectLength();
            $children[] = $newChild;
        }

        $children = [];
        if(null != $contentLength) {
            $octetsToRead = $contentLength;
            while ($octetsToRead > 0) {
                $newChild = Object::fromBinary($binaryData, $offsetIndex);
                $octetsToRead -= $newChild->getObjectLength();
                $children[] = $newChild;
            }
        } else {
            /*try {*/
            for (;;) {
                $newChild = Object::fromBinary($binaryData, $offsetIndex);
                if($newChild instanceof EOC)
                    break;
                $children[] = $newChild;
            }
            $contentLength = abs($startPos - $offsetIndex); // undefined lengths are represented as negative values
            /*            } catch (\Exception $e) {
                            $e->getMessage();
                        }*/
        }

        $this->contentLength = $contentLength;

        parent::__construct(...$children);
    }

    public function getType()
    {
        return ord($this->identifier);
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    protected function calculateContentLength()
    {
        return $this->contentLength;
    }

    protected function getEncodedValue()
    {
        return '';
    }
}
