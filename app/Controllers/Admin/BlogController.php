<?php

namespace App\Controllers\Admin;

use App\Models\BlogPost;

class BlogController extends AdminBaseController
{
    public function index(): void
    {
        $perPage = 20;
        $model   = new BlogPost();
        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $pgn     = paginate($model->count(), $perPage, $page);

        $this->adminView('admin/blog/index', [
            'adminPageTitle' => 'Blog Posts',
            'flash'          => getFlash(),
            'posts'          => $model->getAllPaged($pgn['current_page'], $perPage),
            'pagination'     => $pgn,
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/blog/create', [
            'adminPageTitle' => 'New Blog Post',
            'errors'         => [],
            'data'           => $this->defaults(),
            'categories'     => BLOG_CATEGORIES,
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
                $thumbnail = handleUpload($_FILES['thumbnail'], 'blog');
            } catch (\RuntimeException $e) {
                $errors[] = 'Image upload failed: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/blog/create', [
                'adminPageTitle' => 'New Blog Post',
                'errors'         => $errors,
                'data'           => $data,
                'categories'     => BLOG_CATEGORIES,
            ]);
            return;
        }

        $db    = \Database::getInstance();
        $model = new BlogPost();
        $slug  = uniqueSlug($db, generateSlug($data['title']), 'blog_posts');

        $model->create(array_merge($data, ['slug' => $slug, 'thumbnail' => $thumbnail]));
        flashMessage('success', 'Blog post created successfully.');
        $this->redirect(BASE_URL . '/admin/blog');
    }

    public function edit(array $params): void
    {
        $id   = (int)($params['id'] ?? 0);
        $post = (new BlogPost())->getById($id);

        if (!$post) {
            flashMessage('error', 'Blog post not found.');
            $this->redirect(BASE_URL . '/admin/blog');
        }

        $this->adminView('admin/blog/edit', [
            'adminPageTitle' => 'Edit Blog Post',
            'errors'         => [],
            'data'           => $post,
            'categories'     => BLOG_CATEGORIES,
        ]);
    }

    public function update(array $params): void
    {
        verifyCsrf();

        $id    = (int)($params['id'] ?? 0);
        $model = new BlogPost();
        $post  = $model->getById($id);

        if (!$post) {
            flashMessage('error', 'Post not found.');
            $this->redirect(BASE_URL . '/admin/blog');
        }

        $data   = $this->collectPost();
        $errors = $this->validate($data);

        $thumbnail = $post['thumbnail'];
        if (!empty($_FILES['thumbnail']['name'])) {
            try {
                $newThumb = handleUpload($_FILES['thumbnail'], 'blog');
                if ($post['thumbnail'] && str_starts_with($post['thumbnail'], 'uploads/')) {
                    deleteUpload($post['thumbnail']);
                }
                $thumbnail = $newThumb;
            } catch (\RuntimeException $e) {
                $errors[] = 'Image upload failed: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/blog/edit', [
                'adminPageTitle' => 'Edit Blog Post',
                'errors'         => $errors,
                'data'           => array_merge($post, $data, ['thumbnail' => $thumbnail]),
                'categories'     => BLOG_CATEGORIES,
            ]);
            return;
        }

        $db   = \Database::getInstance();
        $slug = $post['slug'];
        if ($data['title'] !== $post['title']) {
            $slug = uniqueSlug($db, generateSlug($data['title']), 'blog_posts', $id);
        }

        $model->update($id, array_merge($data, ['slug' => $slug, 'thumbnail' => $thumbnail]));
        flashMessage('success', 'Blog post updated.');
        $this->redirect(BASE_URL . '/admin/blog');
    }

    public function destroy(array $params): void
    {
        verifyCsrf();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/admin/blog');
        }

        $id    = (int)($params['id'] ?? 0);
        $model = new BlogPost();
        $post  = $model->getById($id);

        if (!$post) {
            flashMessage('error', 'Post not found.');
            $this->redirect(BASE_URL . '/admin/blog');
        }

        if ($post['thumbnail'] && str_starts_with($post['thumbnail'], 'uploads/')) {
            deleteUpload($post['thumbnail']);
        }

        $model->delete($id);
        flashMessage('success', 'Blog post deleted.');
        $this->redirect(BASE_URL . '/admin/blog');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function defaults(): array
    {
        return [
            'title'     => '',
            'category'  => BLOG_CATEGORIES[0],
            'excerpt'   => '',
            'body'      => '',
            'read_time' => 3,
            'published' => 0,
            'thumbnail' => null,
        ];
    }

    private function collectPost(): array
    {
        $rawCat = $_POST['category'] ?? '';
        return [
            'title'     => trim($_POST['title'] ?? ''),
            'category'  => in_array($rawCat, BLOG_CATEGORIES, true) ? $rawCat : BLOG_CATEGORIES[0],
            'excerpt'   => trim($_POST['excerpt'] ?? ''),
            'body'      => $_POST['body'] ?? '',
            'read_time' => (int)($_POST['read_time'] ?? 3),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($data['title'] === '') $errors[] = 'Title is required.';
        if ($data['body']  === '') $errors[] = 'Body is required.';
        return $errors;
    }
}
