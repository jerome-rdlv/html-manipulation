<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use DOMNode;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * Trait NodeTrait
 * @property \DOMDocument $ownerDocument
 */
trait NodeTrait
{
    /** @var DOMXPath */
    private $xpath = null;

    /** @var CssSelectorConverter */
    private $cssc = null;

    /**
     * @param $selector
     * @return NodeList
     */
    public function findAll($selector)
    {
        if ($this->xpath === null) {
            $this->xpath = new DOMXPath($this->ownerDocument);
        }
        if ($this->cssc === null) {
            $this->cssc = new CssSelectorConverter(true);
        }
        return new NodeList(
            $this->xpath->query($this->cssc->toXPath($selector), $this)
        );
    }

    /**
     * @param string $selector
     * @return Element|null
     */
    public function find($selector)
    {
        $nodes = $this->findAll($selector);
        if ($nodes->count() < 1) {
            return null;
        }
        /** @var Element $first */
        $first = $nodes->item(0);
        return $first;
    }

    public function empty()
    {
        while ($this->firstChild) {
            $this->removeChild($this->firstChild);
        }
    }

    public function innerHtml($html = null): string
    {
        if ($html !== null) {
            // update inner HTML
            $this->empty();
            if ($html) {
                $fragment = $this->ownerDocument->createDocumentFragment();
                $fragment->appendXML('<div>' . $html . '</div>');
                while ($fragment->firstChild && $fragment->firstChild->firstChild) {
                    $this->appendChild($fragment->firstChild->firstChild);
                }
            }
        }
        
        // return inner HTML
        $output = '';
        /** @var DOMNode $node */
        foreach ($this->childNodes as $node) {
            $output .= $this->ownerDocument->saveHTML($node);
        }
        return $output;
    }

    public function outerHtml()
    {
        return $this->ownerDocument->saveHTML($this);
    }
}