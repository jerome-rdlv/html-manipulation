<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use DOMElement;

/**
 * @property ElementList $children
 * @property ClassList $classList
 * @property string $id
 * @property string $name
 * @property static $nextElementSibling
 * @property static $previousElementSibling
 * @property static $firstElementChild
 */
class Element extends DOMElement implements NodeInterface
{
    use NodeTrait;

    private ClassList $classList;

    public function __construct(string $qualifiedName, ?string $value = null, string $namespace = '')
    {
        parent::__construct($qualifiedName, $value, $namespace);
        $this->classList = new ClassList($this);
    }

    public function __get($name)
    {
        $method = 'get'.ucfirst($name);
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }
        return match ($name) {
            'classList' => new ClassList($this),
            'id' => $this->getAttribute('id'),
            'name' => $this->getAttribute('name'),
            default => null,
        };
    }

    public function __set($name, $value)
    {
        /** @noinspection PhpSwitchStatementWitSingleBranchInspection */
        switch ($name) {
            case 'id':
                $this->setAttribute('id', $value);
        }
    }

    public function setAttribute(string $qualifiedName, string $value): static
    {
        parent::setAttribute($qualifiedName, $value);
        return $this;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function getChildren(): ElementList
    {
        $nodes = [];
        foreach ($this->childNodes as $node) {
            if ($node instanceof Element) {
                $nodes[] = $node;
            }
        }
        return new ElementList($nodes);
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function getNextElementSibling(): ?Element
    {
        $node = $this;
        while ($node = $node->nextSibling) {
            if ($node instanceof Element) {
                return $node;
            }
        }
        return null;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function getPreviousElementSibling(): ?Element
    {
        $node = $this;
        while ($node = $node->previousSibling) {
            if ($node instanceof Element) {
                return $node;
            }
        }
        return null;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function getFirstElementChild(): ?Element
    {
        foreach ($this->childNodes as $node) {
            if ($node instanceof Element) {
                return $node;
            }
        }
        return null;
    }
}