<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\ParentNode;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class AddClass implements TransformInterface
{
    private string $query;
    private array $class;

    public function __construct(string $query, string|array $class)
    {
        $this->query = $query;
        $this->class = array_filter(is_array($class) ? $class : explode(' ', $class));
    }

    public function run(ParentNode $node): void
    {
        foreach ($node->querySelectorAll($this->query) as $element) {
            foreach ($this->class as $class) {
                $element->classList->add($class);
            }
        }
    }
}