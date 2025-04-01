<?php

namespace Rdlv\WordPress\HtmlManipulation\Test\Transform;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ContentTransform;
use Rdlv\WordPress\HtmlManipulation\Transform\ChangeHeadingLevel;

class ChangeHeadingLevelTest extends TestCase
{
    public const string TEST_HTML = '<h1>Title</h1><p>Lorem ipsum dolor sit amet.</p><h2>Subtitle</h2>';

    public function testIncreaseLevelByOne(): void
    {
        $this->assertSame(
            '<h2>Title</h2><p>Lorem ipsum dolor sit amet.</p><h3>Subtitle</h3>',
            new ContentTransform()->run(
                '<h1>Title</h1><p>Lorem ipsum dolor sit amet.</p><h2>Subtitle</h2>',
                [new ChangeHeadingLevel(1)]
            )
        );
    }

    public function testIncreaseLevelByTwo(): void
    {
        $this->assertSame(
            '<h3>Title</h3><p>Lorem ipsum dolor sit amet.</p><h4>Subtitle</h4>',
            new ContentTransform()->run(
                '<h1>Title</h1><p>Lorem ipsum dolor sit amet.</p><h2>Subtitle</h2>',
                [new ChangeHeadingLevel(2)]
            )
        );
    }

    public function testDecreaseLevelByOne(): void
    {
        $this->assertSame(
            '<h1>Title</h1><p>Lorem ipsum dolor sit amet.</p><h2>Subtitle</h2>',
            new ContentTransform()->run(
                '<h2>Title</h2><p>Lorem ipsum dolor sit amet.</p><h3>Subtitle</h3>',
                [new ChangeHeadingLevel(-1)]
            )
        );
    }
}