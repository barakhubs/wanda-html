<?php

namespace App\Controllers\Admin;

use App\Models\HomeGallery;

class GalleryController extends AdminBaseController
{
    public function index(): void
    {
        $perPage = 24;
        $model   = new HomeGallery();
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $pgn     = paginate($model->count(), $perPage, $page);

        $this->adminView('admin/gallery/index', [
            'adminPageTitle' => 'Home Gallery',
            'flash'          => getFlash(),
            'images'         => $model->getAllPaged($pgn['current_page'], $perPage),
            'pagination'     => $pgn,
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/gallery/create', [
            'adminPageTitle' => 'Upload Gallery Image',
            'errors'         => [],
            'data'           => ['alt_text' => '', 'is_wide' => 0, 'sort_order' => 0, 'published' => 1],
        ]);
    }

    public function store(): void
    {
        verifyCsrf();

        $data   = [
            'alt_text'   => trim($_POST['alt_text'] ?? ''),
            'is_wide'    => isset($_POST['is_wide']) ? 1 : 0,
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'published'  => isset($_POST['published']) ? 1 : 0,
        ];
        $errors    = [];
        $imagePath = null;

        if (empty($_FILES['image']['name'])) {
            $errors[] = 'An image is required.';
        } else {
            try {
                $imagePath = handleUpload($_FILES['image'], 'gallery');
            } catch (\RuntimeException $e) {
                $errors[] = 'Upload failed: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/gallery/create', [
                'adminPageTitle' => 'Upload Gallery Image',
                'errors'         => $errors,
                'data'           => $data,
            ]);
            return;
        }

        (new HomeGallery())->create(array_merge($data, ['image_path' => $imagePath]));
        flashMessage('success', 'Image uploaded.');
        $this->redirect(BASE_URL . '/admin/gallery');
    }

    public function destroy(array $params): void
    {
        verifyCsrf();

        $id    = (int)($params['id'] ?? 0);
        $model = new HomeGallery();
        $img   = $model->getById($id);

        if (!$img) {
            flashMessage('error', 'Image not found.');
            $this->redirect(BASE_URL . '/admin/gallery');
        }

        if ($img['image_path']) {
            deleteUpload($img['image_path']);
        }

        $model->delete($id);
        flashMessage('success', 'Image deleted.');
        $this->redirect(BASE_URL . '/admin/gallery');
    }
}
