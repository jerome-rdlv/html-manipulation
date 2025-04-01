<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\WrapAdjacent;

class WrapAdjacentTest extends TestCase
{
    public function testWrap(): void
    {
        $this->assertSame(
            '<div class="cols"><p>Lorem ipsum.</p><p>Dolor sit amet.</p></div>',
            new ContentTransform()->run(
                '<p>Lorem ipsum.</p><p>Dolor sit amet.</p>',
                [new WrapAdjacent('p', 'cols')]
            )
        );
    }
}