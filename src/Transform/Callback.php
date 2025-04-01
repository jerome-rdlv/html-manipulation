<?php

namespace Rdlv\WordPress\HtmlManipulation\Transform;

use Dom\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class Callback implements TransformInterface
{
    private string $selector;
    private $callback;

    public function __construct(string $selector, callable $callback)
    {
        $this->selector = $selector;
        $this->callback = $callback;
    }

    public function run(Element $root): void
    {
        foreach ($root->querySelectorAll($this->selector) as $element) {
            call_user_func($this->callback, $element);
        }
    }
}
