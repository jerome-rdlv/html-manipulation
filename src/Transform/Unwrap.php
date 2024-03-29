<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class Unwrap implements TransformInterface
{
    private string $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    public function run($doc): void
    {
        $parents = $doc->querySelectorAll($this->query);

        $toDelete = array();

        foreach ($parents as $parent) {
            $children = [];
            foreach ($parent->childNodes as $node) {
                $children[] = $node;
            }
            foreach ($children as $child) {
                $parent->parentNode->insertBefore($child, $parent);
            }
            $toDelete[] = $parent;
        }

        foreach ($toDelete as $parent) {
            /* @var $node Element */
            $parent->parentNode->removeChild($parent);
        }
    }
}