<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class RemoveClass implements TransformInterface
{
    private string $query;
    private string $class;

    public function __construct(string $query, string $class)
    {
        $this->query = $query;
        $this->class = $class;
    }

    public function run(Element $root): void
    {
        foreach ($root->querySelectorAll($this->query) as $element) {
            $element->classList->remove($this->class);
        }
    }
}
