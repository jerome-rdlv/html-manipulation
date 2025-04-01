<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\ParentNode;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class SetId implements TransformInterface
{
    private string $query;
    private string $id;

    public function __construct(string $query, string $id)
    {
        $this->query = $query;
        $this->id = $id;
    }

    public function run(ParentNode $node): void
    {
        foreach ($node->querySelectorAll($this->query) as $element) {
            $element->id = $this->id;
        }
    }
}