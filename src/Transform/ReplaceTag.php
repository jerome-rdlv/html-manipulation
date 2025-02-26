<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

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

    public function run($doc): void
    {
        $doc->querySelectorAll($this->query)->each(function (Element $element) {
            $this->class && $element->classList->add($this->class);
            Util::changeTag($element, $this->tag);
        });
    }
}