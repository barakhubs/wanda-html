<?php

namespace App\Controllers;

use App\Core\View;

/**
 * Base controller — all controllers extend this.
 * Provides view rendering and redirect helpers.
 */
abstract class BaseController
{
    /**
     * Render a view with the main layout.
     *
     * @param string               $template  e.g. 'blog/index'
     * @param array<string, mixed> $data
     * @param string|null          $layout    'main' | 'admin' | null
     */
    protected function view(string $template, array $data = [], ?string $layout = 'main'): void
    {
        View::render($template, $data, $layout);
    }

    /** Send an HTTP redirect and stop execution. */
    protected function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}
