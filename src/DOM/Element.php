<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

class Element extends \DOMElement
{
    use NodeTrait;
    
    public function __get($name)
    {
        $method = 'get'. ucfirst($name);
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }
    }

    public function setAttribute($name, $value)
    {
        parent::setAttribute($name, $value);
        return $this;
    }

    public function addClass($classes)
    {
        $existing = explode(' ', $this->getAttribute('class'));
        $toAdd = explode(' ', $classes);

        $this->setAttribute(
            'class',
            trim(implode(' ', array_unique(array_merge($existing, $toAdd))))
        );
    }

    public function removeClass($classes)
    {
        $existing = explode(' ', $this->getAttribute('class'));
        $toRemove = explode(' ', $classes);

        $this->setAttribute(
            'class',
            trim(implode(' ', array_diff($existing, $toRemove)))
        );
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
    
    public function getNextElementSibling()
    {
        $node = $this;
        while ($node = $node->nextSibling) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                return $node;
            }
        }
        return null;
    }

    public function getPreviousElementSibling()
    {
        $node = $this;
        while ($node = $node->previousSibling) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                return $node;
            }
        }
        return null;
    }
    
    public function getFirstElementChild()
    {
        foreach ($this->childNodes as $node) {
            if ($node->nodeType === XML_ELEMENT_NODE) {
                return $node;
            }
        }
        return null;
    }
}