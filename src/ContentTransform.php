<?php

namespace Rdlv\WordPress\HtmlManipulation;

class ContentTransform
{
    /**
     * @var TransformInterface[]
     */
    private array $transforms = [];

    /**
     * @param TransformInterface[] $transforms
     */
    public function __construct(array $transforms = [])
    {
        $this->transforms = $transforms;
    }

    /**
     * @param TransformInterface[] $transform
     */
    public function addTransform(array $transform): void
    {
        $this->transforms[] = $transform;
    }

    public function run(string $source, ?array $transforms = null): string
    {
        $transforms = $transforms ?? $this->transforms;

        if (!$transforms || !$source) {
            return $source;
        }

        $document = Util::createDocumentFromHtmlFragment($source);

        foreach ($transforms as $transform) {
            if (is_callable($transform)) {
                call_user_func($transform, $document);
            } elseif ($transform instanceof TransformInterface) {
                $transform->run($document->body);
            }
        }

        return Util::dumpHtmlFragment($document);
    }
}


