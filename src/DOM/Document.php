<?php /** @noinspection ALL */

namespace Rdlv\WordPress\HtmlManipulation\DOM;

use DOMDocument;
use DOMException;
use DOMXPath;
use Masterminds\HTML5;
use Symfony\Component\CssSelector\CssSelectorConverter;

class Document extends DOMDocument implements NodeInterface
{
    public const PARSER_NATIVE = 'native';
    public const PARSER_HTML5 = 'html5';

    private const TEMPLATE_NATIVE = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><body>%s</body>';
    private const TEMPLATE_HTML5 = '<!DOCTYPE html><html><meta charset="UTF-8"/><body>%s</body></html>';

    private DOMXPath $xpath;
    private CssSelectorConverter $cssc;

    public string $parser = self::PARSER_NATIVE;

    private ?Element $body = null;

    public static function create(string $source, int $options = LIBXML_NOERROR): self
    {
        $doc = new self();
        $doc->loadHTML($source, $options);
        return $doc;
    }

    public function __construct()
    {
        parent::__construct('1.0', 'utf-8');

        $this->registerNodeClass('DOMElement', 'Rdlv\WordPress\HtmlManipulation\DOM\Element');
        $this->registerNodeClass('DOMNode', 'Rdlv\WordPress\HtmlManipulation\DOM\Node');
        $this->registerNodeClass('DOMText', 'Rdlv\WordPress\HtmlManipulation\DOM\Text');
        $this->registerNodeClass('DOMComment', 'Rdlv\WordPress\HtmlManipulation\DOM\Comment');
        $this->registerNodeClass('DOMDocumentFragment', 'Rdlv\WordPress\HtmlManipulation\DOM\Fragment');

        $this->cssc = new CssSelectorConverter();
    }

    public function loadHTML(string $source, int $options = LIBXML_NOERROR): static
    {
        switch ($this->parser) {
            case self::PARSER_HTML5:
                $html5 = new HTML5();
                $html5->loadHTML(
                    sprintf(self::TEMPLATE_HTML5, $source),
                    [
                        'target_document' => $this,
                        'disable_html_ns' => true,
                    ]
                );
                break;
            default:
                @parent::loadHTML(sprintf(self::TEMPLATE_NATIVE, $source));
        }
        $this->xpath = new DOMXPath($this);
        $this->body = $this->getElementsByTagName('body')->item(0);
        return $this;
    }

    public function __get(string $name)
    {
        return match ($name) {
            'body', 'head' => $this->getElementsByTagName($name)->item(0),
            default => null,
        };
    }

    public function querySelectorAll(string $selector): ElementList
    {
        return new ElementList(
            $this->xpath->query(
                $this->cssc->toXPath($selector)
            )
        );
    }

    public function querySelector(string $selector): ?Element
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->xpath->query(
            $this->cssc->toXPath($selector)
        )?->item(0);
    }

    public function html(): string
    {
        return $this->body?->innerHtml() ?? '';
    }

    /**
     * @param  string[]  $parts  Classes to add to parts
     * @throws DOMException
     */
    public function split(array $parts, string $splitSelector = 'hr'): static
    {
        $boundaries = iterator_to_array($this->querySelectorAll($splitSelector));

        // create parts elements
        $parts = array_map(function (string $class) {
            /** @var Element $part */
            $part = $this->createElement('div');
            $part->classList->add($class);
            return $part;
        }, $parts);

        $i = 0;
        $body = $this->querySelector('body');
        while ($child = $body->firstChild) {
            if (in_array($child, $boundaries, true)) {
                $i = min($i + 1, count($parts) - 1);
                /** @noinspection PhpPossiblePolymorphicInvocationInspection */
                $child->remove();
                continue;
            }
            $parts[$i]->appendChild($child);
        }

        foreach ($parts as $part) {
            $body->appendChild($part);
        }

        return $this;
    }
}