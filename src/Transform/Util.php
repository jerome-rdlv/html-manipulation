<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Element;

class Util
{
    public static function changeTag(Element $element, $tag): Element
    {
        $doc = $element->ownerDocument;

        /** @var Element $new */
        $new = $doc->createElement($tag);

        // copy attributes
        foreach ($element->attributes as $attribute) {
            $new->setAttribute($attribute->name, $attribute->value);
        }

        // copy children
        while ($element->childNodes->length) {
            $new->appendChild($element->childNodes->item(0));
        }

        // replace element
        $element->parentNode->replaceChild($new, $element);
        
        return $new;
    }
}