<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\ReplaceTag;

class ReplaceTagTest extends TestCase
{
    public function testReplaceTag(): void
    {
        $this->assertSame(
            '<h1>Title</h1>',
            new ContentTransform()->run(
                '<p>Title</p>',
                [new ReplaceTag('p', 'h1')]
            )
        );
    }

    public function testReplaceTagWithNewClass(): void
    {
        $this->assertSame(
            '<h1 class="content__title">Title</h1>',
            new ContentTransform()->run(
                '<p>Title</p>',
                [new ReplaceTag('p', 'h1', 'content__title')]
            )
        );
    }
}