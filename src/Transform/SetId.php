<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Document;
use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\DOM\Node;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class SetId implements TransformInterface
{
    private $query;
    private $id;

    public function __construct($query, $id)
    {
        $this->query = $query;
        $this->id = $id;
    }

    public function run($doc)
    {
        /** @var Document $doc */
        $nodes = $doc->findAll($this->query);

        /** @var Node $node */
        foreach ($nodes as $node) {
            if ($node instanceof Element) {
                $node->setAttribute('id', $this->id);
            }
        }
    }
}