<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\DOM\Document;
use Rdlv\WordPress\HtmlManipulation\DOM\Element;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class ReplaceTag implements TransformInterface
{
    private $query;
    private $tag;
    private $class;
    
    public function __construct($query, $tag, $class = null)
    {
        $this->query = $query;
        $this->tag = $tag;
        $this->class = $class;
    }

    public function run($doc)
    {
        /** @var Document $doc */
        $elements = $doc->findAll($this->query);

        /** @var Element $element */
        foreach ($elements as $element) {
            if ($this->class) {
                $element->addClass($this->class);
            }
            Util::changeTag($element, $this->tag);
        }
    }
}