<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\Element;
use Dom\ParentNode;
use Dom\Text;
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

    public function run(ParentNode $node): void
    {
        $elements = $node->querySelectorAll($this->query);

        if ($elements->count()) {
            $candidates = [];
            foreach ($elements as $candidate) {
                $candidates[] = $candidate;
            }

            $node = $candidates[0]->ownerDocument;

            while (count($candidates) > 0) {
                $candidate = $candidates[0];

                // create new parent element
                $new = $node->createElement($this->tag ?: $candidate->tagName);
                foreach ($candidate->attributes as $attribute) {
                    $new->setAttribute($attribute->name, $attribute->value);
                }
                if ($this->class) {
                    $new->setAttribute('class', $this->class);
                }

                // append new element to document
                $candidate->parentNode->insertBefore($new, $candidate);

                do {
                    $inArray = in_array($candidate, $candidates, true);
                    $next = $candidate->nextSibling;

                    if ($candidate instanceof Text) {
                        $new->appendChild($candidate);
                    } elseif ($inArray) {
                        // one of the candidates
                        while ($candidate->childNodes->length) {
                            $new->appendChild($candidate->childNodes->item(0));
                        }
                        // remove from document
                        $candidate->parentNode->removeChild($candidate);
                    } else {
                        // eligible node between
                        $new->appendChild($candidate);
                    }

                    // drop node from candidates
                    if ($inArray) {
                        array_shift($candidates);
                    }

                    $candidate = $next;
                } while (
                    $candidate && (
                        ($candidate instanceof Text && preg_match('/^ *$/', $candidate->textContent))
                        ||
                        in_array($candidate, $candidates, true)
                        ||
                        ($candidate instanceof Element && $candidate->tagName === 'br')
                    )
                );
            }
        }
    }
}