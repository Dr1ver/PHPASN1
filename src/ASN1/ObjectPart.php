<?php
/**
 * Created by PhpStorm.
 * User: 109
 * Date: 23.10.2015
 * Time: 10:02
 */

namespace FG\ASN1;

abstract class ObjectPart
{
    public $binaryData;

    public function getNrOfOctets(): int
    {
        return strlen($this->binaryData);
    }

    public function getBinary(): string
    {
        return $this->binaryData;
    }
}