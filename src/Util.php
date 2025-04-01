<?php


namespace Rdlv\WordPress\HtmlManipulation;


use Dom\Element;
use Dom\HTMLDocument;

class Util
{
    public const string TEMPLATE_HTML5 = '<!DOCTYPE html><html><meta charset="UTF-8"/><body>%s</body></html>';

    /**
     * Loading HTML fragment with an HTML template to prevent warnings
     */
    public static function createDocumentFromHtmlFragment(string $fragment): HTMLDocument
    {
        return HTMLDocument::createFromString(sprintf(self::TEMPLATE_HTML5, $fragment));
    }

    public static function dumpHtmlFragment(HTMLDocument $document): string
    {
        return $document->body->innerHTML;
    }

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
