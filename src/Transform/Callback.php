<?php

namespace Rdlv\WordPress\HtmlManipulation\Transform;

use Rdlv\WordPress\HtmlManipulation\DOM\Document;
use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class Callback implements TransformInterface
{
    private $selector;
    private $callback;

    public function __construct(string $selector, callable $callback)
    {
        $this->selector = $selector;
        $this->callback = $callback;
    }

    public function run($doc)
    {
        /** @var Document $doc */
        $nodes = $doc->findAll($this->selector);

        /** @var Node $node */
        foreach ($nodes as $node) {
            if ($node instanceof Element) {
                call_user_func($this->callback, $node);
            }
        }
    }
}