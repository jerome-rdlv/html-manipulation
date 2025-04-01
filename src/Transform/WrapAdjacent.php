<?php

namespace Rdlv\WordPress\HtmlManipulation\Transform;

use Dom\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class WrapAdjacent implements TransformInterface
{
    private $filter;

    public function __construct(
        private readonly string $selector = 'p:not([class])',
        private readonly string $class = 'columns',
        ?callable $filter = null
    ) {
        $this->filter = $filter;
    }

    public function run(Element $root): void
    {
        $wrapper = null;
        $node = $root->firstChild;
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
