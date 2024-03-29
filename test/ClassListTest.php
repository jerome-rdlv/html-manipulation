<?php

namespace Rdlv\WordPress\HtmlManipulation\Test;

use PHPUnit\Framework\TestCase;
use Rdlv\WordPress\HtmlManipulation\ClassList;
use Rdlv\WordPress\HtmlManipulation\DOM\Document;

class ClassListTest extends TestCase
{
    public function testParse(): void
    {
        $this->assertSame(
            ['class1', 'class2', 'class3'],
            ClassList::parse('  class1 class2   class1 class3   ')
        );
    }

    public function testSerialize(): void
    {
        $this->assertSame(
            'class1 class2 class3',
            ClassList::serialize(['class1', 'class2', 'class3', 'class1', null, false, 'class2'])
        );
    }

    public function testAddRemoveContains(): void
    {
        $doc = Document::create('<div></div>');
        $element = $doc->querySelector('div');
        $list = (new ClassList($element));

        $list->add('class1 class2  class1 class3   ');
        $this->assertSame(
            'class1 class2 class3',
            (string)$list
        );
        $this->assertSame(
            'class1 class2 class3',
            $element->getAttribute('class')
        );

        $list->remove('  class2 class2 ');
        $this->assertSame(
            'class1 class3',
            (string)$list
        );
        $this->assertSame(
            'class1 class3',
            $element->getAttribute('class')
        );

        $list->toggle('class2 class3');
        $this->assertSame(
            'class1 class2',
            (string)$list
        );

        $this->assertTrue($list->contains(' class1 class2'));
        $this->assertFalse($list->contains('class1 class3'));
    }

}
