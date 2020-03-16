<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use DOMDocument;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

class Document extends DOMDocument
{
    const CHARSET_META = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';

    /** @var DOMXPath */
    private $xpath;

    /** @var CssSelectorConverter */
    private $cssc;

    public function __construct($content)
    {
        parent::__construct('1.0', 'utf-8');

        $this->registerNodeClass('DOMElement', 'Rdlv\WordPress\HtmlManipulation\DOM\Element');
        $this->registerNodeClass('DOMNode', 'Rdlv\WordPress\HtmlManipulation\DOM\Node');
        $this->registerNodeClass('DOMText', 'Rdlv\WordPress\HtmlManipulation\DOM\Text');
        $this->registerNodeClass('DOMComment', 'Rdlv\WordPress\HtmlManipulation\DOM\Comment');

        if ($content && is_string($content)) {
            $this->loadHTML(self::CHARSET_META . $content);
        }

        $this->xpath = new DOMXPath($this);
        $this->cssc = new CssSelectorConverter();
    }

    /**
     * @param string $selector
     * @return NodeList
     */
    public function findAll($selector)
    {
        return new NodeList($this->xpath->query(
            $this->cssc->toXPath($selector)
        ));
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

    /**
     * @return string
     */
    public function html()
    {
        $output = '';
        $body = $this->find('body');
        if ($body) {
            /** @var Node $node */
            foreach ($body->childNodes as $node) {
                $output .= $node->outerHtml();
            }
        }
        return $output;
    }
}