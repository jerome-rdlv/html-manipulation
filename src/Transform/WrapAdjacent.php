<?php

namespace Rdlv\WordPress\HtmlManipulation\Transform;

use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class WrapAdjacent implements TransformInterface
{
    public function __construct(
        private string $selector = 'p:not([class])',
        private string $class = 'columns',
        private $filter = null
    ) {
    }

    public function run($doc): void
    {
        $wrapper = null;
        $node = $doc->body->firstChild;
        while ($node) {
            if (!$node instanceof Element) {
                $next = $node->nextSibling;
                $wrapper && $wrapper->appendChild($node);
                $node = $next;
                continue;
            }
            if (is_callable($this->filter) && !call_user_func($this->filter, $node)) {
                $wrapper = null;
                $node = $node->nextSibling;
                continue;
            }
            if (!$node->matches($this->selector)) {
                $wrapper = null;
                $node = $node->nextSibling;
                continue;
            }
            if (!$wrapper) {
                /** @var Element $wrapper */
                $wrapper = $node->ownerDocument->createElement('div');
                $wrapper->classList->add($this->class);
                $node->parentNode->insertBefore($wrapper, $node);
            }
            $next = $node->nextSibling;
            $wrapper->appendChild($node);
            $node = $next;
        }
    }
}
