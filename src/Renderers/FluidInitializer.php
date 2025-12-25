<?php

declare(strict_types=1);

namespace Jdifool\Tempest\View\Renderers;

use Tempest\Container\Container;
use Tempest\Container\DynamicInitializer;
use Tempest\Container\Singleton;
use Tempest\Reflection\ClassReflector;
use TYPO3Fluid\Fluid\View\TemplateView;
use TYPO3Fluid\Fluid\Core\Cache\SimpleFileCache;
use UnitEnum;

use function Tempest\internal_storage_path;
use function Tempest\Support\Filesystem\ensure_directory_exists;

final readonly class FluidInitializer implements DynamicInitializer
{
    public function canInitialize(ClassReflector $class, null|string|UnitEnum $tag): bool
    {
        if (! class_exists(TemplateView::class)) {
            return false;
        }

        return $class->getName() === TemplateView::class;
    }

    #[Singleton]
    public function initialize(ClassReflector $class, null|string|UnitEnum $tag, Container $container): object
    {
        $fluidConfig = $container->get(FluidConfig::class);

        $view = new TemplateView();

        $cachePath = internal_storage_path($fluidConfig->cachePath ?? 'cache/fluid');
        ensure_directory_exists($cachePath);
        $view->getRenderingContext()->setCache(new SimpleFileCache($cachePath));
        
        $paths = $view->getRenderingContext()->getTemplatePaths();
        $paths->setTemplateRootPaths($fluidConfig->templateRootPaths);
        $paths->setPartialRootPaths($fluidConfig->partialRootPaths);
        $paths->setLayoutRootPaths($fluidConfig->layoutRootPaths);
        
        return $view;
    }
}
