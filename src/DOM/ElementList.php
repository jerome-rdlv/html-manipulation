<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use ArrayIterator;
use Countable;
use DOMNodeList;
use IteratorAggregate;
use Traversable;

class ElementList implements IteratorAggregate, Countable
{
    /** @var Element[] */
    private array $elements;

    /**
     * @param  DOMNodeList|Element[]  $elements
     */
    public function __construct(DOMNodeList|array $elements = [])
    {
        $this->elements = $elements instanceof DOMNodeList ? iterator_to_array($elements) : $elements;
    }

    public function item(int $index): ?Element
    {
        return $this->elements[$index] ?? null;
    }
    
    public function each(callable $callable): static
    {
        array_walk($this->elements, $callable);
        return $this;
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }

    public function html(): string
    {
        return array_reduce($this->elements, function (string $html, Element $node) {
            return $html.$node->ownerDocument->saveHTML($node);
        }, '');
    }

    public function contains(Element $needle): bool
    {
        return in_array($needle, $this->elements, true);
    }
}