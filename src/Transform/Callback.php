<?php

namespace Rdlv\WordPress\HtmlManipulation\Transform;

use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\DOM\Node;
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

    public function run($doc): void
    {
        $doc->querySelectorAll($this->selector)->each($this->callback);
    }
}