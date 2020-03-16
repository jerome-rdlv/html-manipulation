<?php


namespace Rdlv\WordPress\HtmlManipulation;


use Rdlv\WordPress\HtmlManipulation\DOM\Document;

interface TransformInterface
{
    /**
     * @param Document $doc
     */
    public function run($doc);
}