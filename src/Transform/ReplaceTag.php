<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;
use Rdlv\WordPress\HtmlManipulation\Util;

class ReplaceTag implements TransformInterface
{
    private string $query;
    private string $tag;
    private ?string $class;

    public function __construct(string $query, string $tag, ?string $class = null)
    {
        $this->query = $query;
        $this->tag = $tag;
        $this->class = $class;
    }

    public function run(Element $root): void
    {
        foreach ($root->querySelectorAll($this->query) as $element) {
            $this->class && $element->classList->add($this->class);
            Util::changeTag($element, $this->tag);
        }
    }
}
