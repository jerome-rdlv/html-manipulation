<?php

namespace Rdlv\WordPress\HtmlManipulation;

use Rdlv\WordPress\HtmlManipulation\DOM\Document;

class ContentTransform
{
    /**
     * @var TransformInterface[]
     */
    private $transforms = [];

    /**
     * @param TransformInterface[] $transforms
     */
    public function __construct($transforms = [])
    {
        $this->transforms = $transforms;
    }

    /**
     * @var TransformInterface[]
     */
    public function add_transform($transform)
    {
        $this->transforms[] = $transform;
    }

    /**
     * @param string $content
     * @return string
     */
    public function run($content, $transforms = null)
    {
        $doc = new Document($content);

        if ($transforms === null) {
            $transforms = $this->transforms;
        }

        foreach ($transforms as $transform) {
            if (is_callable($transform)) {
                call_user_func($transform, $doc);
            } elseif ($transform instanceof TransformInterface) {
                $transform->run($doc);
            }
        }

        return $doc->html();
    }
}


