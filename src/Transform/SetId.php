<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\Element;
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

    public function run(Element $root): void
    {
        foreach ($root->querySelectorAll($this->query) as $element) {
            $element->id = $this->id;
        }
    }
}
