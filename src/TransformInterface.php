<?php


namespace Rdlv\WordPress\HtmlManipulation;


use Dom\Element;

interface TransformInterface
{
    public function run(Element $root);
}
