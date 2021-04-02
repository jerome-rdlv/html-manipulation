<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Document;
use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\DOM\Node;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class PregReplaceContent implements \Rdlv\WordPress\HtmlManipulation\TransformInterface
{
    private $query;
    private $search;
    private $replace;

    public function __construct($query, $search, $replace)
    {
        $this->query = $query;
        $this->search = $search;
        $this->replace = $replace;
    }

    /**
     * @inheritDoc
     */
    public function run($doc)
    {
        /** @var Document $doc */
        $nodes = $doc->findAll($this->query);

        /** @var Node $node */
        foreach ($nodes as $node) {
            if ($node instanceof Element) {
                $node->innerHtml(preg_replace('#<br *>#', '<br/>', preg_replace(
                    $this->search,
                    $this->replace,
                    $node->innerHtml()
                )));
            }
        }
    }
}