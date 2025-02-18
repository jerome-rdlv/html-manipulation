<?php /** @noinspection PhpComposerExtensionStubsInspection */

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @property Document $ownerDocument
 */
trait NodeTrait
{
    private ?DOMXPath $xpath = null;
    private ?CssSelectorConverter $cssc = null;

    public function querySelectorAll(string $selector): ElementList
    {
        if ($this->xpath === null) {
            $this->xpath = new DOMXPath($this->ownerDocument);
        }
        if ($this->cssc === null) {
            $this->cssc = new CssSelectorConverter(true);
        }
        return new ElementList(
            $this->xpath->query(
                $this->cssc->toXPath($selector),
                $this
            )
        );
    }

    public function querySelector(string $selector): ?Element
    {
        $nodes = $this->querySelectorAll($selector);
        return $nodes->item(0);
    }

    public function empty(): static
    {
        while ($this->firstChild) {
            $this->removeChild($this->firstChild);
        }
        return $this;
    }

    public function isEmpty(bool $ignoreSpaces = true): bool
    {
        foreach ($this->childNodes as $child) {
            if (!$child instanceof Text) {
                return false;
            }
            if ($ignoreSpaces ? trim($child->textContent) : $child->textContent) {
                // node contains text
                return false;
            }
        }
        return true;
    }

    public function hasOnly(string $tag, int $limit = 1, bool $ignoreSpaces = true): bool
    {
        $count = 0;
        foreach ($this->childNodes as $child) {
            if ($child instanceof Text) {
                if ($ignoreSpaces ? trim($child->textContent) : $child->textContent) {
                    // node contains text
                    return false;
                }
                continue;
            }
            if ($child instanceof Element) {
                if ($child->tagName === $tag) {
                    ++$count;
                    if ($count > $limit) {
                        // node contains more than $limit
                        return false;
                    }
                    continue;
                } else {
                    // node contains another element than $tag
                    return false;
                }
            }
        }
        return true;
    }

    public function closest(string $selector): ?Element
    {
        $elements = $this->ownerDocument->querySelectorAll($selector);
        $node = $this;
        while ($node instanceof Element && !$elements->contains($node)) {
            $node = $node->parentNode;
        }
        return $node instanceof Element ? $node : null;
    }

    public function matches(string $selector): bool
    {
        $elements = $this->ownerDocument->querySelectorAll($selector);
        return $elements->contains($this);
    }

    public function innerHtml($html = null): string
    {
        if ($html !== null) {
            // update inner HTML
            $this->empty();
            if ($html) {
                // see https://stackoverflow.com/questions/2778110/change-innerhtml-of-a-php-domelement/47083440#47083440
                $internal_errors = libxml_use_internal_errors(true);
                $fragment = Document::create($html);
                libxml_clear_errors();
                libxml_use_internal_errors($internal_errors);
                foreach ($fragment->body->childNodes as $child) {
                    $this->appendChild($this->ownerDocument->importNode($child, true));
                }
            }
        }

        // return inner HTML
        $output = '';
        /** @var Node $node */
        foreach ($this->childNodes as $node) {
            $output .= $this->ownerDocument->saveHTML($node);
        }
        return $output;
    }

    public function outerHtml(): false|string
    {
        return $this->ownerDocument->saveHTML($this);
    }
}