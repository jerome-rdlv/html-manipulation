<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class ChangeHeadingLevel implements TransformInterface
{
    public const HEADING_SELECTOR = 'h1, h2, h3, h4, h5, h6';

    private int $increment;
    private string $selector;

    public function __construct(int $increment = -1, string $selector = self::HEADING_SELECTOR)
    {
        $this->increment = $increment;
        $this->selector = $selector;
    }

    public function run($doc): void
    {
        $doc->querySelectorAll($this->selector)->each(function (Element $element) {
            if (preg_match('/h([0-9])/', $element->tagName, $m)) {
                Util::changeTag($element, 'h'.max(1, min(6, ((int)$m[1] + $this->increment))));
            }
        });
    }
}