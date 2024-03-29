<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Element;
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

    public function run($doc): void
    {
        $doc->querySelectorAll($this->query)->each(function (Element $element) {
            $element->classList->remove($this->class);
        });
    }
}