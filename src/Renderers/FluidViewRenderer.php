<?php

declare(strict_types=1);

namespace Jdifool\Tempest\View\Renderers;

use Tempest\View\View;
use Tempest\View\ViewRenderer;
use TYPO3Fluid\Fluid\View\TemplateView;

final readonly class FluidViewRenderer implements ViewRenderer
{
    public function __construct(
        private TemplateView $fluid
    ) {}

    public function render(View|string|null $view): string
    {
        if ($view === null) {
            return '';
        }
        
        $path = (is_string($view)) ? $view : $view->path;
        $isAbsPath = file_exists($path);
        $data = (is_string($view)) ? null : $view->data;

        if (is_array($data)) {
            $this->fluid->assignMultiple($data);
        }

        if($isAbsPath){
            $this->fluid->getRenderingContext()->getTemplatePaths()->setTemplatePathAndFilename($path);
            return $this->fluid->render();
        }

        return $this->fluid->render($path);
    }
}
