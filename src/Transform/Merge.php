<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\DOM\Text;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class Merge implements TransformInterface
{
    private string $query;
    private ?string $tag;
    private ?string $class;

    public function __construct(string $query, ?string $tag = null, ?string $class = null)
    {
        $this->query = $query;
        $this->tag = $tag;
        $this->class = $class;
    }

    public function run($doc): void
    {
        $nodes = $doc->querySelectorAll($this->query);

        if ($nodes->count()) {
            $candidates = [];
            foreach ($nodes as $node) {
                $candidates[] = $node;
            }

            $doc = $candidates[0]->ownerDocument;

            while (count($candidates) > 0) {
                $node = $candidates[0];

                // create new parent element
                $new = $doc->createElement($this->tag ?: $node->tagName);
                foreach ($node->attributes as $attribute) {
                    $new->setAttribute($attribute->name, $attribute->value);
                }
                if ($this->class) {
                    $new->setAttribute('class', $this->class);
                }

                // append new element to document
                $node->parentNode->insertBefore($new, $node);

                do {
                    $inArray = in_array($node, $candidates, true);
                    $next = $node->nextSibling;

                    if ($node instanceof Text) {
                        $new->appendChild($node);
                    } elseif ($inArray) {
                        // one of the candidates
                        while ($node->childNodes->length) {
                            $new->appendChild($node->childNodes->item(0));
                        }
                        // remove from document
                        $node->parentNode->removeChild($node);
                    } else {
                        // eligible node between
                        $new->appendChild($node);
                    }

                    // drop node from candidates
                    if ($inArray) {
                        array_shift($candidates);
                    }

                    $node = $next;
                } while (
                    $node && (
                        ($node instanceof Text && preg_match('/^ *$/', $node->textContent))
                        ||
                        in_array($node, $candidates, true)
                        ||
                        ($node instanceof Element && $node->tagName === 'br')
                    )
                );
            }
        }
    }
}