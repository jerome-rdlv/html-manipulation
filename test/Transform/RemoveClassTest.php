<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\RemoveClass;

class RemoveClassTest extends TestCase
{
    public function testRemoveClass(): void
    {
        $this->assertSame(
            '<h1 class="content__title">Title</h1>',
            new ContentTransform()->run(
                '<h1 class="content__title content__subtitle">Title</h1>',
                [new RemoveClass('h1', 'content__subtitle')]
            )
        );
    }
}