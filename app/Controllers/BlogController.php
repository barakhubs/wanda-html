<?php

namespace App\Controllers;

use App\Models\BlogPost;

class BlogController extends BaseController
{
    private const PER_PAGE = 9;

    public function index(): void
    {
        $model    = new BlogPost();
        $total    = $model->count();
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $cat      = $_GET['cat'] ?? '';

        // Filter by category or return all published
        if ($cat !== '' && in_array($cat, BLOG_CATEGORIES, true)) {
            $posts = $this->getByCategory($model, $cat);
            $total = count($posts);
            $paged = array_slice($posts, ($page - 1) * self::PER_PAGE, self::PER_PAGE);
        } else {
            $cat   = '';
            $posts = $model->getPublished();
            $total = count($posts);
            $paged = array_slice($posts, ($page - 1) * self::PER_PAGE, self::PER_PAGE);
        }

        $pagination = paginate($total, self::PER_PAGE, $page);

        $this->view('blog/index', [
            'pageTitle'   => 'Blog | Wanda Communications Uganda',
            'pageDesc'    => 'Insights, stories, and perspectives on strategic communication, advocacy, and development from the Wanda Communications team.',
            'currentPage' => 'blog',
            'posts'       => $paged,
            'pagination'  => $pagination,
            'currentCat'  => $cat,
            'categories'  => BLOG_CATEGORIES,
        ]);
    }

    public function show(array $params): void
    {
        $model = new BlogPost();
        $slug  = $params['slug'] ?? '';
        $post  = $model->getBySlug($slug);

        if (!$post) {
            http_response_code(404);
            $this->view('errors/404', [], 'main');
            return;
        }

        $sidebar = $model->getSidebarPosts((int)$post['id'], 4);

        $this->view('blog/show', [
            'pageTitle'   => e($post['title']) . ' | Wanda Communications',
            'pageDesc'    => $post['excerpt'],
            'currentPage' => 'blog',
            'post'        => $post,
            'sidebar'     => $sidebar,
            'thumbSrc'    => !empty($post['thumbnail']) ? BASE_URL . '/' . $post['thumbnail'] : null,
        ]);
    }

    private function getByCategory(BlogPost $model, string $cat): array
    {
        $all = $model->getPublished();
        return array_values(array_filter($all, fn($p) => $p['category'] === $cat));
    }
}
