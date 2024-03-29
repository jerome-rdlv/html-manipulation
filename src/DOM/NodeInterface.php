<?php

namespace Rdlv\WordPress\HtmlManipulation\DOM;

interface NodeInterface
{
    public function querySelectorAll(string $selector): ElementList;
    public function querySelector(string $selector): ?Element;
}