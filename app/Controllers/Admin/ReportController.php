<?php

namespace App\Controllers\Admin;

use App\Models\Report;

class ReportController extends AdminBaseController
{
    public function index(): void
    {
        $this->adminView('admin/reports/index', [
            'adminPageTitle' => 'Reports',
            'flash'          => getFlash(),
            'reports'        => (new Report())->getAll(),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/reports/create', [
            'adminPageTitle' => 'New Report',
            'errors'         => [],
            'data'           => $this->defaults(),
            'categories'     => REPORT_CATEGORIES,
        ]);
    }

    public function store(): void
    {
        verifyCsrf();

        $data   = $this->collectPost();
        $errors = $this->validate($data);

        if (empty($_FILES['pdf_file']['name'])) {
            $errors[] = 'A PDF file is required.';
        }

        $pdfPath = null;
        if (empty($errors) || !empty($_FILES['pdf_file']['name'])) {
            if (!empty($_FILES['pdf_file']['name'])) {
                try {
                    $pdfPath = handleUpload($_FILES['pdf_file'], 'reports');
                } catch (\RuntimeException $e) {
                    $errors[] = 'PDF upload failed: ' . $e->getMessage();
                }
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/reports/create', [
                'adminPageTitle' => 'New Report',
                'errors'         => $errors,
                'data'           => $data,
                'categories'     => REPORT_CATEGORIES,
            ]);
            return;
        }

        $db   = \Database::getInstance();
        $slug = uniqueSlug($db, generateSlug($data['title']), 'reports');

        (new Report())->create(array_merge($data, ['slug' => $slug, 'pdf_path' => $pdfPath]));

        flashMessage('success', 'Report created successfully.');
        $this->redirect(BASE_URL . '/admin/reports');
    }

    public function edit(array $params): void
    {
        $id     = (int)($params['id'] ?? 0);
        $report = (new Report())->getById($id);

        if (!$report) {
            flashMessage('error', 'Report not found.');
            $this->redirect(BASE_URL . '/admin/reports');
        }

        $this->adminView('admin/reports/edit', [
            'adminPageTitle' => 'Edit Report',
            'errors'         => [],
            'data'           => $report,
            'categories'     => REPORT_CATEGORIES,
        ]);
    }

    public function update(array $params): void
    {
        verifyCsrf();

        $id     = (int)($params['id'] ?? 0);
        $model  = new Report();
        $report = $model->getById($id);

        if (!$report) {
            flashMessage('error', 'Report not found.');
            $this->redirect(BASE_URL . '/admin/reports');
        }

        $data    = $this->collectPost();
        $errors  = $this->validate($data);
        $pdfPath = $report['pdf_path'];

        if (!empty($_FILES['pdf_file']['name'])) {
            try {
                $newPdf = handleUpload($_FILES['pdf_file'], 'reports');
                deleteUpload($report['pdf_path']);
                $pdfPath = $newPdf;
            } catch (\RuntimeException $e) {
                $errors[] = 'PDF upload failed: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/reports/edit', [
                'adminPageTitle' => 'Edit Report',
                'errors'         => $errors,
                'data'           => array_merge($report, $data, ['pdf_path' => $pdfPath]),
                'categories'     => REPORT_CATEGORIES,
            ]);
            return;
        }

        $db   = \Database::getInstance();
        $slug = $report['slug'];
        if ($data['title'] !== $report['title']) {
            $slug = uniqueSlug($db, generateSlug($data['title']), 'reports', $id);
        }

        $model->update($id, array_merge($data, ['slug' => $slug, 'pdf_path' => $pdfPath]));
        flashMessage('success', 'Report updated.');
        $this->redirect(BASE_URL . '/admin/reports');
    }

    public function destroy(array $params): void
    {
        verifyCsrf();

        $id     = (int)($params['id'] ?? 0);
        $model  = new Report();
        $report = $model->getById($id);

        if (!$report) {
            flashMessage('error', 'Report not found.');
            $this->redirect(BASE_URL . '/admin/reports');
        }

        deleteUpload($report['pdf_path']);
        $model->delete($id);

        flashMessage('success', 'Report deleted.');
        $this->redirect(BASE_URL . '/admin/reports');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function defaults(): array
    {
        return [
            'title'     => '',
            'category'  => REPORT_CATEGORIES[0],
            'excerpt'   => '',
            'published' => 0,
            'pdf_path'  => null,
        ];
    }

    private function collectPost(): array
    {
        $rawCat = $_POST['category'] ?? '';
        return [
            'title'     => trim($_POST['title'] ?? ''),
            'category'  => in_array($rawCat, REPORT_CATEGORIES, true) ? $rawCat : REPORT_CATEGORIES[0],
            'excerpt'   => trim($_POST['excerpt'] ?? ''),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($data['title']   === '') $errors[] = 'Title is required.';
        if ($data['excerpt'] === '') $errors[] = 'Excerpt is required.';
        return $errors;
    }
}
