<?php

declare(strict_types=1);

namespace Jdifool\Tempest\View\Tests;

use Jdifool\Tempest\View\Renderers\FluidConfig;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FluidConfigTest extends TestCase
{
    #[Test]
    public function it_creates_config_with_default_values(): void
    {
        $config = new FluidConfig();

        $this->assertSame([], $config->templateRootPaths);
        $this->assertSame([], $config->partialRootPaths);
        $this->assertSame([], $config->layoutRootPaths);
        $this->assertNull($config->cachePath);
    }

    #[Test]
    public function it_creates_config_with_template_paths(): void
    {
        $config = new FluidConfig(
            templateRootPaths: ['/path/to/templates', '/another/path']
        );

        $this->assertSame(['/path/to/templates', '/another/path'], $config->templateRootPaths);
        $this->assertSame([], $config->partialRootPaths);
        $this->assertSame([], $config->layoutRootPaths);
    }

    #[Test]
    public function it_creates_config_with_all_paths(): void
    {
        $config = new FluidConfig(
            templateRootPaths: ['/templates'],
            partialRootPaths: ['/partials'],
            layoutRootPaths: ['/layouts'],
        );

        $this->assertSame(['/templates'], $config->templateRootPaths);
        $this->assertSame(['/partials'], $config->partialRootPaths);
        $this->assertSame(['/layouts'], $config->layoutRootPaths);
    }

    #[Test]
    public function it_creates_config_with_custom_cache_path(): void
    {
        $config = new FluidConfig(
            cachePath: '/custom/cache/path'
        );

        $this->assertSame('/custom/cache/path', $config->cachePath);
    }

    #[Test]
    public function it_creates_config_with_false_cache_path(): void
    {
        $config = new FluidConfig(
            cachePath: false
        );

        $this->assertFalse($config->cachePath);
    }

    #[Test]
    public function it_creates_config_with_multiple_root_paths(): void
    {
        $config = new FluidConfig(
            templateRootPaths: ['/primary/templates', '/fallback/templates', '/vendor/templates'],
            partialRootPaths: ['/primary/partials', '/fallback/partials'],
            layoutRootPaths: ['/primary/layouts'],
        );

        $this->assertCount(3, $config->templateRootPaths);
        $this->assertCount(2, $config->partialRootPaths);
        $this->assertCount(1, $config->layoutRootPaths);
    }
}
