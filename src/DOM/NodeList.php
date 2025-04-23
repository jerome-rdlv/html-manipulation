<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use Countable;
use DOMNode;
use DOMNodeList;
use Iterator;

class NodeList implements Iterator, Countable
{
    /** @var DOMNodeList */
    private $nodes;

    private $index = 0;

    /**
     * NodeList constructor.
     * @param DOMNodeList $nodes
     */
    public function __construct($nodes = null)
    {
        if ($nodes instanceof DOMNodeList) {
            $this->nodes = $nodes;
        } else {
            $this->nodes = new DOMNodeList();
        }
    }

    public function innerHtml()
    {
        $output = '';
        /** @var DOMNode $node */
        foreach ($this->nodes as $node) {
            $output .= $node->ownerDocument->saveHTML($node);
        }
        return $output;
    }

    public function count(): int
    {
        return $this->nodes->length;
    }

    public function item($index)
    {
        return $this->nodes->item($index);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->nodes->item($this->index);
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
    public function key()
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
        return $this->nodes->item($this->index) !== null;
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