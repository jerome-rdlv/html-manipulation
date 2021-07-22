<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Document;
use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\DOM\Node;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class RemoveClass implements TransformInterface
{
    private $query;
    private $class;
    
    public function __construct($query, $class)
    {
        $this->query = $query;
        $this->class = $class;
    }

    public function run($doc)
    {
        /** @var Document $doc */
        $nodes = $doc->findAll($this->query);

        /** @var Node $node */
        foreach ($nodes as $node) {
            if ($node instanceof Element) {
                $node->removeClass($this->class);
            }
        }
    }
}