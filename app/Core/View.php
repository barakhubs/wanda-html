<?php

namespace App\Core;

/**
 * Minimal view renderer.
 *
 * Renders a PHP template from app/Views/, injecting variables
 * and optionally wrapping it in a layout.
 *
 * Usage (inside a controller):
 *   View::render('blog/index', ['posts' => $posts]);
 *   View::render('admin/blog/create', $data, 'admin');
 */
class View
{
    /** Base path to the views directory */
    private static string $viewsPath = '';

    public static function init(string $viewsPath): void
    {
        self::$viewsPath = rtrim($viewsPath, '/\\');
    }

    /**
     * Render a view, optionally inside a layout.
     *
     * @param string               $template  Relative path without extension, e.g. "blog/index"
     * @param array<string, mixed> $data      Variables to extract into the template scope
     * @param string|null          $layout    Layout name under app/Views/layouts/, or null for no layout
     */
    public static function render(string $template, array $data = [], ?string $layout = 'main'): void
    {
        $viewFile = self::$viewsPath . '/' . $template . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View not found: {$viewFile}");
        }

        // Capture the inner content first
        $content = self::capture($viewFile, $data);

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = self::$viewsPath . '/layouts/' . $layout . '.php';

        if (!file_exists($layoutFile)) {
            // Fall back to raw output if layout is missing
            echo $content;
            return;
        }

        // Inject $content into the layout scope
        $data['content'] = $content;
        self::capture($layoutFile, $data, true);
    }

    /**
     * Capture template output into a string (or echo directly when $output=false).
     *
     * @param array<string, mixed> $data
     */
    private static function capture(string $file, array $data, bool $echo = false): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        $output = (string) ob_get_clean();

        if ($echo) {
            echo $output;
        }

        return $output;
    }
}
