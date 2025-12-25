<?php

declare(strict_types=1);

namespace Jdifool\Tempest\View\Tests;

use Jdifool\Tempest\View\Renderers\FluidConfig;
use Jdifool\Tempest\View\Renderers\FluidInitializer;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Framework\Testing\IntegrationTest;
use Tempest\Reflection\ClassReflector;
use TYPO3Fluid\Fluid\View\TemplateView;

final class FluidInitializerTest extends IntegrationTest
{
    private string $fixturesPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixturesPath = __DIR__ . '/Fixtures';
    }

    private function assertPathInArray(string $expectedPath, array $paths): void
    {
        $normalizedExpected = rtrim($expectedPath, '/');
        foreach ($paths as $path) {
            if (rtrim($path, '/') === $normalizedExpected) {
                $this->assertTrue(true);
                return;
            }
        }
        $this->fail("Failed asserting that paths contain '{$expectedPath}'. Paths: " . implode(', ', $paths));
    }

    #[Test]
    public function it_can_initialize_template_view_class(): void
    {
        $initializer = new FluidInitializer();
        $reflector = new ClassReflector(TemplateView::class);

        $this->assertTrue($initializer->canInitialize($reflector, null));
    }

    #[Test]
    public function it_cannot_initialize_other_classes(): void
    {
        $initializer = new FluidInitializer();
        $reflector = new ClassReflector(FluidConfig::class);

        $this->assertFalse($initializer->canInitialize($reflector, null));
    }

    #[Test]
    public function it_initializes_template_view_with_config(): void
    {
        $this->container->config(new FluidConfig(
            templateRootPaths: [$this->fixturesPath . '/Templates'],
            partialRootPaths: [$this->fixturesPath . '/Partials'],
            layoutRootPaths: [$this->fixturesPath . '/Layouts'],
        ));

        $view = $this->container->get(TemplateView::class);

        $this->assertInstanceOf(TemplateView::class, $view);

        $paths = $view->getRenderingContext()->getTemplatePaths();

        $this->assertPathInArray(
            $this->fixturesPath . '/Templates',
            $paths->getTemplateRootPaths()
        );
        $this->assertPathInArray(
            $this->fixturesPath . '/Partials',
            $paths->getPartialRootPaths()
        );
        $this->assertPathInArray(
            $this->fixturesPath . '/Layouts',
            $paths->getLayoutRootPaths()
        );
    }

    #[Test]
    public function it_returns_same_instance_as_singleton(): void
    {
        $this->container->config(new FluidConfig(
            templateRootPaths: [$this->fixturesPath . '/Templates'],
        ));

        $view1 = $this->container->get(TemplateView::class);
        $view2 = $this->container->get(TemplateView::class);

        $this->assertSame($view1, $view2);
    }

    #[Test]
    public function it_configures_multiple_template_root_paths(): void
    {
        $this->container->config(new FluidConfig(
            templateRootPaths: [
                $this->fixturesPath . '/Templates',
                '/another/path',
            ],
        ));

        $view = $this->container->get(TemplateView::class);
        $paths = $view->getRenderingContext()->getTemplatePaths();
        $templatePaths = $paths->getTemplateRootPaths();

        $this->assertPathInArray($this->fixturesPath . '/Templates', $templatePaths);
        $this->assertPathInArray('/another/path', $templatePaths);
    }
}
