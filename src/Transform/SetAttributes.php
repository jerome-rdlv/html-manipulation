<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\ParentNode;
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

    public function run(ParentNode $node): void
    {
        foreach ($node->querySelectorAll($this->query) as $element) {
            foreach ($this->attributes as $attribute => $value) {
                if ($value === null) {
                    $element->removeAttribute($attribute);
                } else {
                    $element->setAttribute($attribute, $value);
                }
            }
        }
    }
}