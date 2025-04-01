<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\SetAttributes;

class SetAttributesTest extends TestCase
{
    public function testSetAttributes(): void
    {
        $this->assertSame(
            '<h1 class="content__title" id="title" data-uri="https://example.org">Title</h1>',
            new ContentTransform()->run('<h1 data-deprecated="dep">Title</h1>', [
                new SetAttributes('h1', [
                    'class' => 'content__title',
                    'id' => 'title',
                    'data-uri' => 'https://example.org',
                    'data-deprecated' => null,
                ])
            ])
        );
    }
}