<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\Unwrap;

class UnwrapTest extends TestCase
{
    public function testUnwrap(): void
    {
        $this->assertSame(
            '<p>Lorem ipsum.</p><p>Dolor sit amet.</p>',
            new ContentTransform()->run(
                '<div><p>Lorem ipsum.</p><p>Dolor sit amet.</p></div>',
                [new Unwrap('div')]
            )
        );
    }
}