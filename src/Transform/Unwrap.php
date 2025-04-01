<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class Unwrap implements TransformInterface
{
    private string $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    public function run(Element $root): void
    {
        $parents = $root->querySelectorAll($this->query);

        $toDelete = array();

        foreach ($parents as $parent) {
            $children = [];
            foreach ($parent->childNodes as $child) {
                $children[] = $child;
            }
            foreach ($children as $child) {
                $parent->parentNode->insertBefore($child, $parent);
            }
            $toDelete[] = $parent;
        }

        foreach ($toDelete as $parent) {
            $parent->parentNode->removeChild($parent);
        }
    }
}
