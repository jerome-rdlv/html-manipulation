<?php

namespace Rdlv\WordPress\HtmlManipulation\Test;

use Dom\ParentNode;
use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\TransformInterface;

class ContentTransformTest extends TestCase
{
    public const string TEST_HTML = '<h1 class="content__title">Title</h1><p>Lorem ipsum dolor sit amet.</p><p>Consectetur adipiscing elit.</p>';

    public function testParseEmpty(): void
    {
        $this->assertEquals(
            self::TEST_HTML,
            new ContentTransform()->run(self::TEST_HTML)
        );
    }

    public function testParseAndDump(): void
    {
        $this->assertEquals(
            self::TEST_HTML,
            new ContentTransform([
                new class implements TransformInterface {
                    public function run(ParentNode $node)
                    {
                    }
                }
            ])->run(self::TEST_HTML)
        );
    }
}
