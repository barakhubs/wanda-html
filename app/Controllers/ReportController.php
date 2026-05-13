<?php

namespace App\Controllers;

use App\Models\Report;

class ReportController extends BaseController
{
    public function index(): void
    {
        $model    = new Report();
        $category = trim($_GET['category'] ?? '');

        $reports = $category && in_array($category, REPORT_CATEGORIES, true)
            ? $model->getPublishedByCategory($category)
            : $model->getPublished();

        $this->view('reports/index', [
            'pageTitle'       => 'Reports | Wanda Communications Uganda',
            'pageDesc'        => 'Download and read research reports, policy briefs, and advocacy publications.',
            'currentPage'     => 'reports',
            'reports'         => $reports,
            'categories'      => REPORT_CATEGORIES,
            'activeCategory'  => $category,
        ]);
    }

    public function show(array $params): void
    {
        $slug   = $params['slug'] ?? '';
        $report = (new Report())->getBySlug($slug);

        if (!$report) {
            http_response_code(404);
            \App\Core\View::render('errors/404', [], 'main');
            return;
        }

        $this->view('reports/show', [
            'pageTitle'   => e($report['title']) . ' | Reports | Wanda Communications Uganda',
            'pageDesc'    => e($report['excerpt']),
            'currentPage' => 'reports',
            'report'      => $report,
        ]);
    }
}
