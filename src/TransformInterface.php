<?php


namespace Rdlv\WordPress\HtmlManipulation;


use Dom\ParentNode;

interface TransformInterface
{
    public function run(ParentNode $node);
}