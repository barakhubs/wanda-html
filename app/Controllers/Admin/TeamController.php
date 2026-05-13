<?php

namespace App\Controllers\Admin;

use App\Models\TeamMember;

class TeamController extends AdminBaseController
{
    private const SKILL_JS = <<<'JS'
<script>
function addSkill(val) {
  val = val || '';
  var wrap = document.getElementById('skills-wrap');
  var row  = document.createElement('div');
  row.className = 'skill-row';
  row.innerHTML = '<input type="text" name="skills[]" value="' + val.replace(/"/g,'&quot;') + '" placeholder="Skill name">'
    + '<button type="button" class="btn-remove-skill" onclick="this.parentElement.remove()"><i class="bi bi-x-lg"></i></button>';
  wrap.appendChild(row);
}
</script>
JS;

    public function index(): void
    {
        $this->adminView('admin/team/index', [
            'adminPageTitle' => 'Team Members',
            'flash'          => getFlash(),
            'members'        => (new TeamMember())->getAllAdmin(),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/team/create', [
            'adminPageTitle'    => 'Add Team Member',
            'adminExtraScripts' => self::SKILL_JS,
            'errors'            => [],
            'data'              => $this->defaults(),
            'skills'            => [''],
            'gradients'         => GRADIENT_OPTIONS,
        ]);
    }

    public function store(): void
    {
        verifyCsrf();

        $data   = $this->collectPost();
        $skills = array_values(array_filter(array_map('trim', (array)($_POST['skills'] ?? []))));
        $errors = $this->validate($data);

        $photo = null;
        if (!empty($_FILES['photo']['name'])) {
            try {
                $photo = handleUpload($_FILES['photo'], 'team');
            } catch (\RuntimeException $e) {
                $errors[] = 'Upload failed: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/team/create', [
                'adminPageTitle'    => 'Add Team Member',
                'adminExtraScripts' => self::SKILL_JS,
                'errors'            => $errors,
                'data'              => $data,
                'skills'            => $skills ?: [''],
                'gradients'         => GRADIENT_OPTIONS,
            ]);
            return;
        }

        (new TeamMember())->create(array_merge($data, ['photo' => $photo, 'skills' => $skills]));
        flashMessage('success', 'Team member added.');
        $this->redirect(BASE_URL . '/admin/team');
    }

    public function edit(array $params): void
    {
        $id     = (int)($params['id'] ?? 0);
        $member = (new TeamMember())->getById($id);

        if (!$member) {
            flashMessage('error', 'Team member not found.');
            $this->redirect(BASE_URL . '/admin/team');
        }

        $skills = $member['skills'] ?? [];
        if (empty($skills)) $skills = [''];

        $this->adminView('admin/team/edit', [
            'adminPageTitle'    => 'Edit Team Member',
            'adminExtraScripts' => self::SKILL_JS,
            'errors'            => [],
            'data'              => $member,
            'skills'            => $skills,
            'gradients'         => GRADIENT_OPTIONS,
        ]);
    }

    public function update(array $params): void
    {
        verifyCsrf();

        $id     = (int)($params['id'] ?? 0);
        $model  = new TeamMember();
        $member = $model->getById($id);

        if (!$member) {
            flashMessage('error', 'Member not found.');
            $this->redirect(BASE_URL . '/admin/team');
        }

        $data   = $this->collectPost();
        $skills = array_values(array_filter(array_map('trim', (array)($_POST['skills'] ?? []))));
        $errors = $this->validate($data);

        $photo = $member['photo'];
        if (!empty($_FILES['photo']['name'])) {
            try {
                $newPhoto = handleUpload($_FILES['photo'], 'team');
                if ($member['photo'] && str_starts_with($member['photo'], 'uploads/')) {
                    deleteUpload($member['photo']);
                }
                $photo = $newPhoto;
            } catch (\RuntimeException $e) {
                $errors[] = 'Upload failed: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/team/edit', [
                'adminPageTitle'    => 'Edit Team Member',
                'adminExtraScripts' => self::SKILL_JS,
                'errors'            => $errors,
                'data'              => array_merge($member, $data, ['photo' => $photo]),
                'skills'            => $skills ?: [''],
                'gradients'         => GRADIENT_OPTIONS,
            ]);
            return;
        }

        $model->update($id, array_merge($data, ['photo' => $photo]));
        $model->saveSkills($id, $skills);
        flashMessage('success', 'Team member updated.');
        $this->redirect(BASE_URL . '/admin/team');
    }

    public function destroy(array $params): void
    {
        verifyCsrf();

        $id     = (int)($params['id'] ?? 0);
        $model  = new TeamMember();
        $member = $model->getById($id);

        if (!$member) {
            flashMessage('error', 'Member not found.');
            $this->redirect(BASE_URL . '/admin/team');
        }

        if ($member['photo'] && str_starts_with($member['photo'], 'uploads/')) {
            deleteUpload($member['photo']);
        }

        $model->delete($id);
        flashMessage('success', 'Team member deleted.');
        $this->redirect(BASE_URL . '/admin/team');
    }

    private function defaults(): array
    {
        return [
            'name'          => '',
            'role'          => '',
            'bio_1'         => '',
            'bio_2'         => '',
            'gradient_css'  => array_key_first(GRADIENT_OPTIONS),
            'fallback_icon' => 'bi-person-fill',
            'sort_order'    => 0,
            'published'     => 1,
            'photo'         => null,
        ];
    }

    private function collectPost(): array
    {
        return [
            'name'          => trim($_POST['name'] ?? ''),
            'role'          => trim($_POST['role'] ?? ''),
            'bio_1'         => trim($_POST['bio_1'] ?? ''),
            'bio_2'         => trim($_POST['bio_2'] ?? ''),
            'gradient_css'  => trim($_POST['gradient_css'] ?? array_key_first(GRADIENT_OPTIONS)),
            'fallback_icon' => trim($_POST['fallback_icon'] ?? 'bi-person-fill'),
            'sort_order'    => (int)($_POST['sort_order'] ?? 0),
            'published'     => isset($_POST['published']) ? 1 : 0,
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];
        if ($data['name'] === '') $errors[] = 'Name is required.';
        if ($data['role'] === '') $errors[] = 'Role is required.';
        return $errors;
    }
}
