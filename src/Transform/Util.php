<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\Element;

class Util
{
    public static function changeTag(Element $element, $tag): Element
    {
        $doc = $element->ownerDocument;

        $new = $doc->createElement($tag);

        // copy attributes
        foreach ($element->attributes as $attribute) {
            $new->setAttribute($attribute->name, $attribute->value);
        }

        // copy children
        while ($element->childNodes->length) {
            $new->append($element->childNodes->item(0));
        }

        // replace element
        $element->replaceWith($new);

        return $new;
    }
}