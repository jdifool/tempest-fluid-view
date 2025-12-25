<?php

declare(strict_types=1);

namespace Jdifool\Tempest\View\Tests;

use Jdifool\Tempest\View\Renderers\FluidConfig;
use Jdifool\Tempest\View\Renderers\FluidViewRenderer;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Framework\Testing\IntegrationTest;
use Tempest\View\GenericView;

final class FluidViewRendererTest extends IntegrationTest
{
    private string $fixturesPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixturesPath = __DIR__ . '/Fixtures';

        $this->container->config(new FluidConfig(
            templateRootPaths: [$this->fixturesPath . '/Templates'],
            partialRootPaths: [$this->fixturesPath . '/Partials'],
            layoutRootPaths: [$this->fixturesPath . '/Layouts'],
        ));
    }

    #[Test]
    public function it_renders_simple_template_with_variable(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $view = new GenericView(
            path: 'Simple',
            data: ['name' => 'World']
        );

        $result = $renderer->render($view);

        $this->assertStringContainsString('<h1>Hello, World!</h1>', $result);
        $this->assertStringContainsString('<div class="simple">', $result);
    }

    #[Test]
    public function it_renders_template_with_layout_and_partials(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $view = new GenericView(
            path: 'Index',
            data: ['title' => 'Fluid Views']
        );

        $result = $renderer->render($view);

        $this->assertStringContainsString('<title>Test Layout</title>', $result);
        $this->assertStringContainsString('<h1>Test Header</h1>', $result);
        $this->assertStringContainsString('Welcome to Fluid Views', $result);
        $this->assertStringContainsString('<p>Test Footer</p>', $result);
    }

    #[Test]
    public function it_renders_template_with_loop(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $view = new GenericView(
            path: 'WithLoop',
            data: ['items' => ['Apple', 'Banana', 'Cherry']]
        );

        $result = $renderer->render($view);

        $this->assertStringContainsString('<li>Apple</li>', $result);
        $this->assertStringContainsString('<li>Banana</li>', $result);
        $this->assertStringContainsString('<li>Cherry</li>', $result);
    }

    #[Test]
    public function it_renders_template_with_conditional_true(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $view = new GenericView(
            path: 'WithConditional',
            data: [
                'showMessage' => true,
                'message' => 'Hello from conditional'
            ]
        );

        $result = $renderer->render($view);

        $this->assertStringContainsString('Message is visible: Hello from conditional', $result);
        $this->assertStringNotContainsString('No message to display', $result);
    }

    #[Test]
    public function it_renders_template_with_conditional_false(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $view = new GenericView(
            path: 'WithConditional',
            data: [
                'showMessage' => false,
                'message' => 'This should not appear'
            ]
        );

        $result = $renderer->render($view);

        $this->assertStringContainsString('No message to display', $result);
        $this->assertStringNotContainsString('Message is visible', $result);
    }

    #[Test]
    public function it_renders_template_with_multiple_variables(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $view = new GenericView(
            path: 'MultiVariable',
            data: [
                'title' => 'Test Article',
                'author' => 'John Doe',
                'content' => 'This is the article content.',
                'year' => 2024
            ]
        );

        $result = $renderer->render($view);

        $this->assertStringContainsString('<h1>Test Article</h1>', $result);
        $this->assertStringContainsString('By John Doe', $result);
        $this->assertStringContainsString('This is the article content.', $result);
        $this->assertStringContainsString('2024', $result);
    }

    #[Test]
    public function it_renders_static_template_without_variables(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $view = new GenericView(
            path: 'NoVariables',
            data: []
        );

        $result = $renderer->render($view);

        $this->assertStringContainsString('This is a static template with no variables.', $result);
    }

    #[Test]
    public function it_returns_empty_string_for_null_view(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $result = $renderer->render(null);

        $this->assertSame('', $result);
    }

    #[Test]
    public function it_renders_template_with_absolute_path(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $absolutePath = $this->fixturesPath . '/Templates/Simple.html';

        $view = new GenericView(
            path: $absolutePath,
            data: ['name' => 'Absolute']
        );

        $result = $renderer->render($view);

        $this->assertStringContainsString('<h1>Hello, Absolute!</h1>', $result);
    }

    #[Test]
    public function it_renders_string_path_directly(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $result = $renderer->render('NoVariables');

        $this->assertStringContainsString('This is a static template with no variables.', $result);
    }

    #[Test]
    public function it_renders_absolute_string_path_directly(): void
    {
        $renderer = $this->container->get(FluidViewRenderer::class);

        $absolutePath = $this->fixturesPath . '/Templates/NoVariables.html';

        $result = $renderer->render($absolutePath);

        $this->assertStringContainsString('This is a static template with no variables.', $result);
    }
}
