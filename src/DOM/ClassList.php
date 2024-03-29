<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use ArrayAccess;
use Countable;

class ClassList implements ArrayAccess, Countable
{
    public static function parse(string $attribute): array
    {
        return array_values(array_unique(preg_split('/\s+/', trim($attribute))));
    }

    public static function serialize(array $classes): string
    {
        return implode(' ', array_unique(array_filter($classes)));
    }

    private static function clean(string|array $class): array
    {
        if (is_string($class)) {
            return self::parse($class);
        }
        return array_values(array_unique(array_filter($class)));
    }

    private Element $element;

    public function __construct(Element $element)
    {
        $this->element = $element;
    }

    /**
     * @param  string|string[]  $class
     * @return void
     */
    public function add(string|array $class): void
    {
        $this->set(array_merge($this->get(), self::clean($class)));
    }

    /**
     * @param  string|string[]  $class
     */
    public function remove(string|array $class): void
    {
        $class = self::clean($class);
        $this->set(array_filter($this->get(), function ($current) use ($class) {
            return !in_array($current, $class);
        }));
    }

    /**
     * @param  string|string[]  $class
     */
    public function toggle(string|array $class): void
    {
        $classes = $this->get();
        foreach (self::clean($class) as $token) {
            if ($offset = array_search($token, $classes)) {
                array_splice($classes, $offset, 1);
            } else {
                $classes[] = $token;
            }
        }
        $this->set($classes);
    }

    /**
     * @param  string|string[]  $class
     */
    public function contains(string|array $class): bool
    {
        $classes = $this->get();
        foreach (self::clean($class) as $token) {
            if (in_array($token, $classes)) {
                continue;
            }
            return false;
        }
        return true;
    }

    private function get(): array
    {
        return self::parse($this->element->getAttribute('class'));
    }

    private function set(array $classes): void
    {
        $this->element->setAttribute('class', self::serialize($classes));
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->get());
    }

    public function offsetGet(mixed $offset): ?string
    {
        return $this->get()[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $classes = $this->get();
        $classes[$offset] = $value;
        $this->set($classes);
    }

    public function offsetUnset(mixed $offset): void
    {
        $classes = $this->get();
        unset($classes[$offset]);
        $this->set($classes);
    }

    public function count(): int
    {
        return count($this->get());
    }

    public function __toString(): string
    {
        return $this->element->getAttribute('class');
    }
}