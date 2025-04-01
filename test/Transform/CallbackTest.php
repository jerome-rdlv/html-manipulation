<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use Dom\Element;
use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\Callback;

class CallbackTest extends TestCase
{
    public function testCallback(): void
    {
        $this->assertSame(
            '<h1>Title updated</h1>',
            new ContentTransform()->run(
                '<h1>Title</h1>',
                [
                    new Callback('h1', function (Element $element) {
                        $element->innerHTML = 'Title updated';
                    })
                ]
            )
        );
    }

}