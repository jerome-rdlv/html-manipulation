<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Element;
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

    public function run($doc): void
    {
        $doc->querySelectorAll($this->query)->each(function (Element $element) {
            $element->id = $this->id;
        });
    }
}