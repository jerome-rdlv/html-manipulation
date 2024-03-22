<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use DOMDocument;
use DOMXPath;
use Masterminds\HTML5;
use Symfony\Component\CssSelector\CssSelectorConverter;

class Document extends DOMDocument
{
    private const TEMPLATE_NATIVE = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><body>%s</body>';
    private const TEMPLATE_HTML5 = '<!DOCTYPE html><html><meta charset="UTF-8"/><body>%s</body></html>';

    const PARSER_NATIVE = 'native';
    const PARSER_HTML5 = 'html5';

    /** @var DOMXPath */
    private $xpath;

    /** @var CssSelectorConverter */
    private $cssc;

    public $parser = self::PARSER_NATIVE;

    public static function create(string $source): self
    {
        $doc = new Document();
        $doc->loadHTML($source);
        return $doc;
    }

    public function __construct()
    {
        parent::__construct('1.0', 'utf-8');

        $this->registerNodeClass('DOMElement', 'Rdlv\WordPress\HtmlManipulation\DOM\Element');
        $this->registerNodeClass('DOMNode', 'Rdlv\WordPress\HtmlManipulation\DOM\Node');
        $this->registerNodeClass('DOMText', 'Rdlv\WordPress\HtmlManipulation\DOM\Text');
        $this->registerNodeClass('DOMComment', 'Rdlv\WordPress\HtmlManipulation\DOM\Comment');

        $this->cssc = new CssSelectorConverter();
    }

    public function loadHTML($source, $options = 0)
    {
        switch ($this->parser) {
            case self::PARSER_HTML5:
                $html5 = new HTML5();
                $html5->loadHTML(
                    sprintf(self::TEMPLATE_HTML5, $source),
                    [
                        'target_document' => $this,
                        'disable_html_ns' => true,
                    ]
                );
                break;
            default:
                @parent::loadHTML(sprintf(self::TEMPLATE_NATIVE, $source));
        }
        $this->xpath = new DOMXPath($this);
    }

    /**
     * @param  string  $selector
     * @return NodeList
     */
    public function findAll($selector)
    {
        return new NodeList(
            $this->xpath->query(
                $this->cssc->toXPath($selector)
            )
        );
    }

    /**
     * @param  string  $selector
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
        $body = $this->getElementsByTagName('body')->item(0);
        return $body ? $body->innerHtml() : '';
    }
}