<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Document;
use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class ChangeHeadingLevel implements TransformInterface
{
    private $increment;
    
    public function __construct($increment = -1)
    {
        $this->increment = $increment;
    }

    public function run($doc)
    {
        /** @var Document $doc */
        $elements = $doc->findAll('h1, h2, h3, h4, h5, h6');

        /** @var Element $element */
        foreach ($elements as $element) {
            preg_match('/h([0-9])/', $element->tagName, $m);
            Util::changeTag($element, 'h' . max(1, min(6, ($m[1] + $this->increment))));
        }
    }
}