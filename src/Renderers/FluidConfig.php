<?php

declare(strict_types=1);

namespace Jdifool\Tempest\View\Renderers;

final readonly class FluidConfig
{
    public function __construct(
        public array $templateRootPaths = [],
        public array $partialRootPaths = [],
        public array $layoutRootPaths = [],
        public null|false|string $cachePath = null
    ) {}
}
