<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\SetId;

class SetIdTest extends TestCase
{
    public function testSetId(): void
    {
        $this->assertSame(
            '<h1 id="title">Title</h1>',
            new ContentTransform()->run(
                '<h1>Title</h1>',
                [new SetId('h1', 'title')]
            )
        );
    }

}