<?php

namespace Rdlv\WordPress\HtmlManipulation;

use Dom\HTMLDocument;

class ContentTransform
{
    public const TEMPLATE_HTML5 = '<!DOCTYPE html><html><meta charset="UTF-8"/><body>%s</body></html>';

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

        // template is needed to have valid HTML and prevent a parser warning
        $document = HTMLDocument::createFromString(sprintf(self::TEMPLATE_HTML5, $source));

        foreach ($transforms as $transform) {
            if (is_callable($transform)) {
                call_user_func($transform, $document);
            } elseif ($transform instanceof TransformInterface) {
                $transform->run($document->body);
            }
        }

        return $document->body->innerHTML;

    }
}


