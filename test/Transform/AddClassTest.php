<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\AddClass;

class AddClassTest extends TestCase
{
    public function testAddSingleClass(): void
    {
        $this->assertSame(
            '<h1 class="content__title">Title</h1>',
            new ContentTransform()->run(
                '<h1>Title</h1>',
                [new AddClass('h1', 'content__title')]
            )
        );
    }

    public function testAddMultipleClassString(): void
    {
        $this->assertSame(
            '<h1 class="content__title content__title--alt">Title</h1>',
            new ContentTransform()->run(
                '<h1>Title</h1>',
                [new AddClass('h1', 'content__title content__title--alt')]
            )
        );
    }

    public function testAddMultipleClassArray(): void
    {
        $this->assertSame(
            '<h1 class="content__title content__title--alt">Title</h1>',
            new ContentTransform()->run(
                '<h1>Title</h1>',
                [new AddClass('h1', ['content__title', 'content__title--alt'])]
            )
        );
    }
}