<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Dom\ParentNode;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class ChangeHeadingLevel implements TransformInterface
{
    public const string HEADING_SELECTOR = 'h1, h2, h3, h4, h5, h6';

    private int $increment;
    private string $selector;

    public function __construct(int $increment = -1, string $selector = self::HEADING_SELECTOR)
    {
        $this->increment = $increment;
        $this->selector = $selector;
    }

    public function run(ParentNode $node): void
    {
        if (!$this->increment) {
            return;
        }
       
        foreach ($node->querySelectorAll($this->selector) as $element) {
            if (preg_match('/H([0-9])/', $element->tagName, $m)) {
                $level = ((int)$m[1] + $this->increment);
                if ($level < 1 || $level > 6) {
                    trigger_error(sprintf(
                        'Transform results in invalid heading level H%d for "%s".',
                        $level,
                        $element->ownerDocument->saveHTML($element)
                    ), E_USER_WARNING);
                }
                Util::changeTag($element, 'h' . max(1, min(6, $level)));
            }
        }
    }
}