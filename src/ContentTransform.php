<?php

namespace Rdlv\WordPress\HtmlManipulation;

use Rdlv\WordPress\HtmlManipulation\DOM\Document;

class ContentTransform
{
    /**
     * @var TransformInterface[]
     */
    private $transforms = [];

    /** @var string */
    public $parser;

    /**
     * @param TransformInterface[] $transforms
     * @param string $parser HTML parser to use. Be careful, HTML5 parser is very slow.
     */
    public function __construct($transforms = [], string $parser = Document::PARSER_NATIVE)
    {
        $this->transforms = $transforms;
        $this->parser = $parser;
    }

    /**
     * @var TransformInterface[]
     */
    public function add_transform($transform)
    {
        $this->transforms[] = $transform;
    }

    /**
     * @param string $source
     * @return string
     */
    public function run($source, $transforms = null, string $parser = null)
    {
        if ($transforms === null) {
            $transforms = $this->transforms;
        }

        if (!$transforms || !$source) {
            return $source;
        }

        $doc = new Document();

//        $start = microtime(true);
        
        $doc->parser = $parser ?: $this->parser;
        $doc->loadHTML($source);

//        trigger_error(sprintf('parse %s in %s ms', $doc->parser, round((microtime(true) - $start) * 1000, 3)));

        foreach ($transforms as $transform) {
            if (is_callable($transform)) {
                call_user_func($transform, $doc);
            } elseif ($transform instanceof TransformInterface) {
                $transform->run($doc);
            }
        }
        
//        trigger_error(sprintf('transform in %s ms', round((microtime(true) - $start) * 1000, 3)));

        return $doc->html();
    }
}


