<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Document;
use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\DOM\Node;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class AddAttributes implements TransformInterface
{
    private $query;
    private $attributes;

    public function __construct($query, $attributes)
    {
        $this->query = $query;
        $this->attributes = $attributes;
    }

    public function run($doc)
    {
        /** @var Document $doc */
        $nodes = $doc->findAll($this->query);

        /** @var Node $node */
        foreach ($nodes as $node) {
            if ($node instanceof Element) {
                foreach ($this->attributes as $attribute => $value) {
                    $node->setAttribute($attribute, $value);
                }
            }
        }
    }
}