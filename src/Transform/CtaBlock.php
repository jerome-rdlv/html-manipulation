<?php


namespace Rdlv\WordPress\HtmlManipulation\Transform;


use Rdlv\WordPress\HtmlManipulation\Element;
use Rdlv\WordPress\HtmlManipulation\Theme;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class CtaBlock implements TransformInterface
{
    private $theme;

    /**
     * @param Theme $theme
     */
    public function __construct($theme)
    {
        $this->theme = $theme;
    }

    public function run($doc)
    {
        $blocks = $doc->findAll('.content__cta');
        /** @var Element $block */
        foreach ($blocks as $block) {
            $links = $block->findAll('a');
            /** @var Element $link */
            foreach ($links as $link) {
                $link->addClass('cta');
                $link->textContent = $this->theme->cta_inner($link->textContent);
            }
        }
    }
}