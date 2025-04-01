<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\PregReplaceContent;

class PregReplaceContentTest extends TestCase
{
    public function testReplace(): void
    {
        $this->assertSame(
            '<p>Great content</p>',
            new ContentTransform()->run(
                '<p>Great title</p>',
                [new PregReplaceContent('p', '/title/', 'content')]
            )
        );
    }
}