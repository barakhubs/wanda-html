<?php

namespace App\Controllers\Admin;

use App\Models\Portfolio;

class PortfolioController extends AdminBaseController
{
    public function index(): void
    {
        $this->adminView('admin/portfolio/index', [
            'adminPageTitle' => 'Portfolio',
            'flash'          => getFlash(),
            'items'          => (new Portfolio())->getAll(),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/portfolio/create', [
            'adminPageTitle' => 'New Portfolio Item',
            'errors'         => [],
            'data'           => $this->defaults(),
            'categories'     => PORTFOLIO_CATEGORIES,
            'gradients'      => GRADIENT_OPTIONS,
        ]);
    }

    public function store(): void
    {
        verifyCsrf();

        $data   = $this->collectPost();
        $errors = $this->validate($data);

        $thumbnail = null;
        if (!empty($_FILES['thumbnail']['name'])) {
            try {
                $thumbnail = handleUpload($_FILES['thumbnail'], 'portfolio');
            } catch (\RuntimeException $e) {
                $errors[] = 'Upload failed: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/portfolio/create', [
                'adminPageTitle' => 'New Portfolio Item',
                'errors'         => $errors,
                'data'           => $data,
                'categories'     => PORTFOLIO_CATEGORIES,
                'gradients'      => GRADIENT_OPTIONS,
            ]);
            return;
        }

        $db   = \Database::getInstance();
        $slug = uniqueSlug($db, generateSlug($data['title']), 'portfolio_items');

        (new Portfolio())->create(array_merge($data, ['slug' => $slug, 'thumbnail' => $thumbnail]));
        flashMessage('success', 'Portfolio item created.');
        $this->redirect(BASE_URL . '/admin/portfolio');
    }

    public function edit(array $params): void
    {
        $id   = (int)($params['id'] ?? 0);
        $item = (new Portfolio())->getById($id);

        if (!$item) {
            flashMessage('error', 'Portfolio item not found.');
            $this->redirect(BASE_URL . '/admin/portfolio');
        }

        $this->adminView('admin/portfolio/edit', [
            'adminPageTitle' => 'Edit Portfolio Item',
            'errors'         => [],
            'data'           => $item,
            'categories'     => PORTFOLIO_CATEGORIES,
            'gradients'      => GRADIENT_OPTIONS,
        ]);
    }

    public function update(array $params): void
    {
        verifyCsrf();

        $id    = (int)($params['id'] ?? 0);
        $model = new Portfolio();
        $item  = $model->getById($id);

        if (!$item) {
            flashMessage('error', 'Item not found.');
            $this->redirect(BASE_URL . '/admin/portfolio');
        }

        $data   = $this->collectPost();
        $errors = $this->validate($data);

        $thumbnail = $item['thumbnail'];
        if (!empty($_FILES['thumbnail']['name'])) {
            try {
                $newThumb = handleUpload($_FILES['thumbnail'], 'portfolio');
                if ($item['thumbnail'] && str_starts_with($item['thumbnail'], 'uploads/')) {
                    deleteUpload($item['thumbnail']);
                }
                $thumbnail = $newThumb;
            } catch (\RuntimeException $e) {
                $errors[] = 'Upload failed: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/portfolio/edit', [
                'adminPageTitle' => 'Edit Portfolio Item',
                'errors'         => $errors,
                'data'           => array_merge($item, $data, ['thumbnail' => $thumbnail]),
                'categories'     => PORTFOLIO_CATEGORIES,
                'gradients'      => GRADIENT_OPTIONS,
            ]);
            return;
        }

        $db   = \Database::getInstance();
        $slug = $item['slug'];
        if ($data['title'] !== $item['title']) {
            $slug = uniqueSlug($db, generateSlug($data['title']), 'portfolio_items', $id);
        }

        $model->update($id, array_merge($data, ['slug' => $slug, 'thumbnail' => $thumbnail]));
        flashMessage('success', 'Portfolio item updated.');
        $this->redirect(BASE_URL . '/admin/portfolio');
    }

    public function destroy(array $params): void
    {
        verifyCsrf();

        $id    = (int)($params['id'] ?? 0);
        $model = new Portfolio();
        $item  = $model->getById($id);

        if (!$item) {
            flashMessage('error', 'Item not found.');
            $this->redirect(BASE_URL . '/admin/portfolio');
        }

        if ($item['thumbnail'] && str_starts_with($item['thumbnail'], 'uploads/')) {
            deleteUpload($item['thumbnail']);
        }

        $model->delete($id);
        flashMessage('success', 'Portfolio item deleted.');
        $this->redirect(BASE_URL . '/admin/portfolio');
    }

    private function defaults(): array
    {
        return [
            'title'        => '',
            'category'     => PORTFOLIO_CATEGORIES[0],
            'short_desc'   => '',
            'full_desc'    => '',
            'gradient_css' => array_key_first(GRADIENT_OPTIONS),
            'icon_class'   => 'bi-camera',
            'featured'     => 0,
            'sort_order'   => 0,
            'published'    => 1,
            'thumbnail'    => null,
        ];
    }

    private function collectPost(): array
    {
        $rawCat = $_POST['category'] ?? '';
        return [
            'title'        => trim($_POST['title'] ?? ''),
            'category'     => in_array($rawCat, PORTFOLIO_CATEGORIES, true) ? $rawCat : PORTFOLIO_CATEGORIES[0],
            'short_desc'   => trim($_POST['short_desc'] ?? ''),
            'full_desc'    => trim($_POST['full_desc'] ?? ''),
            'gradient_css' => trim($_POST['gradient_css'] ?? array_key_first(GRADIENT_OPTIONS)),
            'icon_class'   => trim($_POST['icon_class'] ?? 'bi-camera'),
            'featured'     => isset($_POST['featured']) ? 1 : 0,
            'sort_order'   => (int)($_POST['sort_order'] ?? 0),
            'published'    => isset($_POST['published']) ? 1 : 0,
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($data['title'] === '') $errors[] = 'Title is required.';
        return $errors;
    }
}
