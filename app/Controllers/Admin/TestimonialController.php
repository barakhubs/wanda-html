<?php

namespace App\Controllers\Admin;

use App\Models\Testimonial;

class TestimonialController extends AdminBaseController
{
    public function index(): void
    {
        $this->adminView('admin/testimonials/index', [
            'adminPageTitle' => 'Testimonials',
            'flash'          => getFlash(),
            'testimonials'   => (new Testimonial())->getAll(),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/testimonials/create', [
            'adminPageTitle' => 'Add Testimonial',
            'errors'         => [],
            'data'           => $this->defaults(),
        ]);
    }

    public function store(): void
    {
        verifyCsrf();

        $data   = $this->collectPost();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->adminView('admin/testimonials/create', [
                'adminPageTitle' => 'Add Testimonial',
                'errors'         => $errors,
                'data'           => $data,
            ]);
            return;
        }

        (new Testimonial())->create($data);
        flashMessage('success', 'Testimonial added.');
        $this->redirect(BASE_URL . '/admin/testimonials');
    }

    public function edit(array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $t  = (new Testimonial())->getById($id);

        if (!$t) {
            flashMessage('error', 'Testimonial not found.');
            $this->redirect(BASE_URL . '/admin/testimonials');
        }

        $this->adminView('admin/testimonials/edit', [
            'adminPageTitle' => 'Edit Testimonial',
            'errors'         => [],
            'data'           => $t,
        ]);
    }

    public function update(array $params): void
    {
        verifyCsrf();

        $id    = (int)($params['id'] ?? 0);
        $model = new Testimonial();

        if (!$model->getById($id)) {
            flashMessage('error', 'Testimonial not found.');
            $this->redirect(BASE_URL . '/admin/testimonials');
        }

        $data   = $this->collectPost();
        $errors = $this->validate($data);

        if (!empty($errors)) {
            $this->adminView('admin/testimonials/edit', [
                'adminPageTitle' => 'Edit Testimonial',
                'errors'         => $errors,
                'data'           => $data,
            ]);
            return;
        }

        $model->update($id, $data);
        flashMessage('success', 'Testimonial updated.');
        $this->redirect(BASE_URL . '/admin/testimonials');
    }

    public function destroy(array $params): void
    {
        verifyCsrf();

        $id    = (int)($params['id'] ?? 0);
        $model = new Testimonial();

        if (!$model->getById($id)) {
            flashMessage('error', 'Testimonial not found.');
            $this->redirect(BASE_URL . '/admin/testimonials');
        }

        $model->delete($id);
        flashMessage('success', 'Testimonial deleted.');
        $this->redirect(BASE_URL . '/admin/testimonials');
    }

    private function defaults(): array
    {
        return [
            'quote'           => '',
            'author_initials' => '',
            'author_role'     => '',
            'author_org'      => '',
            'sort_order'      => 0,
            'published'       => 1,
        ];
    }

    private function collectPost(): array
    {
        return [
            'quote'           => trim($_POST['quote'] ?? ''),
            'author_initials' => trim($_POST['author_initials'] ?? ''),
            'author_role'     => trim($_POST['author_role'] ?? ''),
            'author_org'      => trim($_POST['author_org'] ?? ''),
            'sort_order'      => (int)($_POST['sort_order'] ?? 0),
            'published'       => isset($_POST['published']) ? 1 : 0,
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($data['quote']           === '') $errors[] = 'Quote is required.';
        if ($data['author_initials'] === '') $errors[] = 'Author initials are required.';
        return $errors;
    }
}
