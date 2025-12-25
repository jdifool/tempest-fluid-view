# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a TYPO3 Fluid template engine integration for Tempest PHP. It provides `FluidViewRenderer` as an alternative view renderer for the Tempest framework.

## Commands

```bash
# Install dependencies
composer install

# Run all tests
composer test
# or
./vendor/bin/phpunit

# Run a single test file
./vendor/bin/phpunit tests/FluidViewRendererTest.php

# Run a specific test method
./vendor/bin/phpunit --filter testRenderWithStringPath
```

## Architecture

The package consists of three classes in `src/Renderers/`:

- **FluidConfig** - Configuration DTO holding template paths (templates, partials, layouts) and optional cache path. Used via Tempest's config system (`app/fluid.config.php`).

- **FluidInitializer** - Tempest `DynamicInitializer` that bootstraps TYPO3Fluid's `TemplateView` as a singleton. Configures template paths from `FluidConfig` and sets up file caching in Tempest's internal storage.

- **FluidViewRenderer** - Implements Tempest's `ViewRenderer` interface. Handles both absolute file paths and template names (resolved via Fluid's template path configuration). Assigns view data to Fluid before rendering.

## Integration Points

- Implements `Tempest\View\ViewRenderer` interface
- Uses `Tempest\Container\DynamicInitializer` for dependency injection
- Relies on Tempest functions: `internal_storage_path()`, `ensure_directory_exists()`
- Template cache stored in `{storage}/cache/fluid/` by default
