<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\DOM\Node;
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

    public function run($doc): void
    {
        $doc->querySelectorAll($this->query)->each(function (Element $element) {
            $element->innerHtml(
                preg_replace(
                    ['#<br *>#', $this->search],
                    ['<br/>', $this->replace],
                    $element->innerHtml()
                )
            );
        });
    }
}