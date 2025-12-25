# Fluid Template Engine for Tempest PHP

A [TYPO3 Fluid](https://github.com/TYPO3/Fluid) template engine integration for [Tempest PHP](https://tempestphp.com/).

## Requirements

- PHP 8.4+
- Tempest Framework 2.0+

## Installation

```bash
composer require jdifool/tempest-fluid-view
```

## Configuration

### 1. Configure Fluid Paths

Create a configuration file for Fluid template paths:

```php
<?php
// app/fluid.config.php

declare(strict_types=1);

use Jdifool\Tempest\View\Renderers\FluidConfig;

return new FluidConfig(
    templateRootPaths: [
        __DIR__ . '/../views/Templates/',
    ],
    partialRootPaths: [
        __DIR__ . '/../views/Partials/',
    ],
    layoutRootPaths: [
        __DIR__ . '/../views/Layouts/',
    ]
);
```

### 2. Set Fluid as the View Renderer

Update your view configuration to use the Fluid renderer:

```php
<?php
// app/view.config.php

declare(strict_types=1);

use Tempest\View\ViewConfig;
use Jdifool\Tempest\View\Renderers\FluidViewRenderer;

return new ViewConfig(
    rendererClass: FluidViewRenderer::class
);
```

## Usage

Use Fluid templates in your controllers:

```php
<?php
// app/HomeController.php

namespace App;

use Tempest\Router\Get;
use Tempest\View\View;
use function Tempest\view;

final readonly class HomeController
{
    #[Get('/')]
    public function __invoke(): View
    {
        return view('Home/Index', foo: 'bar');
    }
}
```

This renders `views/Templates/Home/Index.html`.

## Resources

- [Fluid Documentation](https://docs.typo3.org/other/typo3fluid/fluid/main/en-us/)
- [Tempest Documentation](https://tempestphp.com/docs/)

## License

MIT
