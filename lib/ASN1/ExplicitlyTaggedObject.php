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

use FG\ASN1\Exception\ParserException;

/**
 * Class ExplicitlyTaggedObject decorate an inner object with an additional tag that gives information about
 * its context specific meaning.
 *
 * Explanation taken from A Layman's Guide to a Subset of ASN.1, BER, and DER:
 * >>> An RSA Laboratories Technical Note
 * >>> Burton S. Kaliski Jr.
 * >>> Revised November 1, 1993
 *
 * [...]
 * Explicitly tagged types are derived from other types by adding an outer tag to the underlying type.
 * In effect, explicitly tagged types are structured types consisting of one component, the underlying type.
 * Explicit tagging is denoted by the ASN.1 keywords [class number] EXPLICIT (see Section 5.2).
 * [...]
 *
 * @see http://luca.ntop.org/Teaching/Appunti/asn1.html
 */
class ExplicitlyTaggedObject extends Object
{
    /** @var \FG\ASN1\Object[] */
    private $decoratedObjects;
    private $tag;

    /**
     * @param int $tag
     * @param \FG\ASN1\Object $objects,...
     */
    public function __construct(Identifier $identifier, ContentLength $contentLength, Content $content, array $children = [])
    {
        parent::__construct($identifier, $contentLength, $content, $children);
    }

    protected function calculateContentLength()
    {
        $length = 0;
        foreach ($this->decoratedObjects as $object) {
            $length += $object->getObjectLength();
        }

        return $length;
    }

    protected function getEncodedValue()
    {
        return $this->getBinaryContent();
    }

    public static function create(int $tagNumber, Object $object, $class = Identifier::CLASS_CONTEXT_SPECIFIC)
    {
        $hasIndefiniteLength = $object->getContentLength()->getLengthForm() === ContentLength::INDEFINITE_FORM;

        return
            ElementBuilder::createObject(
                $class,
                $tagNumber,
                true,
                null,
                $hasIndefiniteLength ? ContentLength::INDEFINITE_FORM : ContentLength::SHORT_FORM,
                [$object]
            );
    }

    public function __toString()
    {
        return '['.$this->getIdentifier()->getTagNumber().']' . (implode("\n", $this->getChildren()));
    }
}
