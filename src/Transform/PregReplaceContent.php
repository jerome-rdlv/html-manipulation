<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class PregReplaceContent implements TransformInterface
{
    private string $query;
    private string $search;
    private string $replace;

    public function __construct(string $query, string $search, string $replace)
    {
        $this->query = $query;
        $this->search = $search;
        $this->replace = $replace;
    }

    public function run(Element $root): void
    {
        foreach ($root->querySelectorAll($this->query) as $element) {
            $element->innerHTML = preg_replace(
                $this->search,
                $this->replace,
                $element->innerHTML
            );
        }
    }
}
