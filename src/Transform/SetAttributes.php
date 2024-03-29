<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class SetAttributes implements TransformInterface
{
    private string $query;
    private array $attributes;

    public function __construct(string $query, array $attributes)
    {
        $this->query = $query;
        $this->attributes = $attributes;
    }

    public function run($doc): void
    {
        $doc->querySelectorAll($this->query)->each(function (Element $element) {
            foreach ($this->attributes as $attribute => $value) {
                if ($value === null) {
                    $element->removeAttribute($attribute);
                } else {
                    $element->setAttribute($attribute, $value);
                }
            }
        });
    }
}