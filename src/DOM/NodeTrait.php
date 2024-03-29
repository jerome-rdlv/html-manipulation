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

    public function querySelector($selector): ?Element
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

    public function innerHtml($html = null): string
    {
        if ($html !== null) {
            // update inner HTML
            $this->empty();
            if ($html) {
                $fragment = $this->ownerDocument->createDocumentFragment();
                libxml_use_internal_errors(true);
                $html = preg_replace('#<br *>#', '<br/>', $html);
                $fragment->appendXML('<div>'.$html.'</div>');
                libxml_clear_errors();
                while ($fragment->firstChild && $fragment->firstChild->firstChild) {
                    $this->appendChild($fragment->firstChild->firstChild);
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