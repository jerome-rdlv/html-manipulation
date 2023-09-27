<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use Countable;
use DOMNode;
use DOMNodeList;
use Iterator;

class NodeList implements Iterator, Countable
{
    /** @var DOMNode[] */
    private $nodes;

    private $index = 0;

    /**
     * NodeList constructor.
     * @param  DOMNodeList|array  $nodes
     */
    public function __construct($nodes = null)
    {
        $this->nodes = $nodes;
    }

    public function innerHtml()
    {
        $output = '';
        foreach ($this->nodes as $node) {
            $output .= $node->ownerDocument->saveHTML($node);
        }
        return $output;
    }

    public function count(): int
    {
        return is_array($this->nodes) ? count($this->nodes) : $this->nodes->length;
    }

    public function item($index)
    {
        if ($index >= 0 && $index < $this->count()) {
            return is_array($this->nodes) ? $this->nodes[$index] : $this->nodes->item($index);
        }
        return null;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current(): mixed
    {
        return $this->item($this->index);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next(): void
    {
        ++$this->index;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key(): mixed
    {
        return $this->valid() ? $this->index : null;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid(): bool
    {
        return $this->index >= 0 && $this->index < $this->count();
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind(): void
    {
        $this->index = 0;
    }
}