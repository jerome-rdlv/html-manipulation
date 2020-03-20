<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

class Element extends \DOMElement
{
    use NodeTrait;

    public function setAttribute($name, $value)
    {
        parent::setAttribute($name, $value);
        return $this;
    }

    public function addClass($classes)
    {
        $olds = explode(' ', $this->getAttribute('class'));
        $news = explode(' ', $classes);

        $news = array_merge($olds, $news);
        $news = array_unique($news);

        $this->setAttribute('class', implode(' ', $news));
    }

    public function hasClass($class)
    {
        return preg_match('/(^|\b)' . preg_quote($class) . '(\b|$)/', $this->getAttribute('class'));
    }

    public function getChildElements()
    {
        $nodes = [];
        foreach ($this->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                $nodes[] = $node;
            }
        }
        return new NodeList($nodes);
    }
}