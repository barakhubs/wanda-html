<?php

namespace App\Controllers\Admin;

use App\Models\Admin;
use App\Core\Mailer;

/**
 * Manages member accounts (admin-only).
 *
 * Routes:
 *   GET  /admin/users              → index()   — list all users
 *   GET  /admin/users/create       → create()  — new member form
 *   POST /admin/users/create       → store()   — save new member
 *   GET  /admin/users/edit/{id}    → edit()    — edit member form
 *   POST /admin/users/edit/{id}    → update()  — save changes
 *   POST /admin/users/delete/{id}  → destroy() — delete member
 */
class UsersController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdminRole();
    }

    // ── List ──────────────────────────────────────────────────────────────────

    public function index(): void
    {
        $this->adminView('admin/users/index', [
            'adminPageTitle' => 'User Management',
            'flash'          => getFlash(),
            'users'          => (new Admin())->getAll(),
        ]);
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): void
    {
        $this->adminView('admin/users/create', [
            'adminPageTitle' => 'Add Member',
            'errors'         => [],
            'data'           => $this->defaults(),
        ]);
    }

    public function store(): void
    {
        verifyCsrf();

        $data   = $this->collectPost();
        $errors = $this->validate($data, true);

        if (!empty($errors)) {
            $this->adminView('admin/users/create', [
                'adminPageTitle' => 'Add Member',
                'errors'         => $errors,
                'data'           => $data,
            ]);
            return;
        }

        // Auto-generate a secure password — the member receives it by email.
        $plainPassword = $this->generatePassword();

        $result = (new Admin())->createMember(
            $data['username'],
            $plainPassword,
            $data['full_name'],
            $data['email']
        );

        if (is_string($result)) {
            $this->adminView('admin/users/create', [
                'adminPageTitle' => 'Add Member',
                'errors'         => [$result],
                'data'           => $data,
            ]);
            return;
        }

        // Send welcome email with login credentials.
        $siteTitle = setting('site_title') ?: 'Wanda Admin';
        $loginUrl  = BASE_URL . '/admin/login';

        $mailResult = Mailer::send(
            $data['email'],
            $data['full_name'],
            "Your {$siteTitle} account credentials",
            $this->welcomeEmailHtml($data['full_name'], $data['username'], $plainPassword, $loginUrl, $siteTitle),
            $this->welcomeEmailText($data['full_name'], $data['username'], $plainPassword, $loginUrl, $siteTitle)
        );

        if ($mailResult !== true) {
            flashMessage('success', "Member account created. The welcome email could not be sent ({$mailResult}) — please share the credentials manually.");
        } else {
            flashMessage('success', "Member account created. Login credentials sent to {$data['email']}.");
        }

        $this->redirect(BASE_URL . '/admin/users');
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(int $id): void
    {
        $user = $this->findMember($id);

        $this->adminView('admin/users/edit', [
            'adminPageTitle' => 'Edit Member',
            'errors'         => [],
            'user'           => $user,
        ]);
    }

    public function update(int $id): void
    {
        verifyCsrf();
        $this->findMember($id); // 404 guard

        $data   = $this->collectPost();
        $errors = $this->validate($data, false); // password optional on edit

        if (!empty($errors)) {
            $this->adminView('admin/users/edit', [
                'adminPageTitle' => 'Edit Member',
                'errors'         => $errors,
                'user'           => array_merge(['id' => $id], $data),
            ]);
            return;
        }

        $result = (new Admin())->updateMember(
            $id,
            $data['username'],
            $data['full_name'],
            $data['email'],
            $data['password'] // may be empty — model handles that
        );

        if (is_string($result)) {
            $this->adminView('admin/users/edit', [
                'adminPageTitle' => 'Edit Member',
                'errors'         => [$result],
                'user'           => array_merge(['id' => $id], $data),
            ]);
            return;
        }

        flashMessage('success', 'Member account updated.');
        $this->redirect(BASE_URL . '/admin/users');
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function destroy(int $id): void
    {
        verifyCsrf();

        $result = (new Admin())->deleteMember($id);

        if (is_string($result)) {
            flashMessage('error', $result);
        } else {
            flashMessage('success', 'Member account deleted.');
        }

        $this->redirect(BASE_URL . '/admin/users');
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * Load a member record by ID; redirect with 404 flash if not found
     * or if the row belongs to a super-admin.
     */
    private function findMember(int $id): array
    {
        $user = (new Admin())->getByIdWithRole($id);

        if (!$user || $user['role'] === 'admin') {
            flashMessage('error', 'Member not found.');
            $this->redirect(BASE_URL . '/admin/users');
        }

        return $user;
    }

    private function defaults(): array
    {
        return ['username' => '', 'full_name' => '', 'email' => ''];
    }

    private function collectPost(): array
    {
        return [
            'username'  => trim($_POST['username']  ?? ''),
            'full_name' => trim($_POST['full_name']  ?? ''),
            'email'     => trim($_POST['email']      ?? ''),
            'password'  => $_POST['password']        ?? '',
        ];
    }

    /**
     * @param bool $isCreate  True when creating (email required, password skipped).
     *                        False when updating (email optional, password optional).
     */
    private function validate(array $data, bool $isCreate): array
    {
        $errors = [];

        if ($data['username'] === '') {
            $errors[] = 'Username is required.';
        } elseif (!preg_match('/^[a-zA-Z0-9_\-]{3,80}$/', $data['username'])) {
            $errors[] = 'Username may only contain letters, numbers, underscores and hyphens (3–80 chars).';
        }

        if ($data['full_name'] === '') {
            $errors[] = 'Full name is required.';
        } elseif (mb_strlen($data['full_name']) > 120) {
            $errors[] = 'Full name must be 120 characters or fewer.';
        }

        if ($isCreate) {
            // Email is mandatory on create — credentials are sent to it.
            if ($data['email'] === '') {
                $errors[] = 'Email address is required to send login credentials.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address.';
            }
        } else {
            // Email and password are optional on update.
            if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address.';
            }
            if ($data['password'] !== '' && strlen($data['password']) < 8) {
                $errors[] = 'Password must be at least 8 characters.';
            }
        }

        return $errors;
    }

    /**
     * Generate a cryptographically random password.
     * Avoids visually ambiguous characters (0, O, l, 1, I).
     */
    private function generatePassword(int $length = 14): string
    {
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789@#!$';
        $max   = strlen($chars) - 1;
        $pass  = '';
        for ($i = 0; $i < $length; $i++) {
            $pass .= $chars[random_int(0, $max)];
        }
        return $pass;
    }

    private function welcomeEmailHtml(
        string $fullName,
        string $username,
        string $password,
        string $loginUrl,
        string $siteTitle
    ): string {
        $n = htmlspecialchars($fullName,  ENT_QUOTES, 'UTF-8');
        $u = htmlspecialchars($username,  ENT_QUOTES, 'UTF-8');
        $p = htmlspecialchars($password,  ENT_QUOTES, 'UTF-8');
        $l = htmlspecialchars($loginUrl,  ENT_QUOTES, 'UTF-8');
        $t = htmlspecialchars($siteTitle, ENT_QUOTES, 'UTF-8');

        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
        <body style="margin:0;padding:0;background:#f4f6fb;font-family:Arial,Helvetica,sans-serif">
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:40px 0">
          <tr><td align="center">
            <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08)">
              <tr>
                <td style="background:#1a6fc4;padding:28px 40px">
                  <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:600">{$t}</h1>
                </td>
              </tr>
              <tr>
                <td style="padding:36px 40px 28px">
                  <p style="margin:0 0 16px;color:#1a202c;font-size:15px">Hi <strong>{$n}</strong>,</p>
                  <p style="margin:0 0 24px;color:#4a5568;font-size:15px;line-height:1.6">
                    An admin account has been created for you on <strong>{$t}</strong>.
                    Use the credentials below to sign in.
                  </p>
                  <table width="100%" cellpadding="0" cellspacing="0"
                         style="background:#f4f6fb;border:1px solid #e2e8f0;border-radius:6px;margin-bottom:24px">
                    <tr>
                      <td style="padding:20px 24px">
                        <p style="margin:0 0 10px;color:#2d3748;font-size:14px">
                          <strong>Login URL:</strong>&nbsp;
                          <a href="{$l}" style="color:#1a6fc4">{$l}</a>
                        </p>
                        <p style="margin:0 0 10px;color:#2d3748;font-size:14px">
                          <strong>Username:</strong>&nbsp; {$u}
                        </p>
                        <p style="margin:0;color:#2d3748;font-size:14px">
                          <strong>Password:</strong>&nbsp; {$p}
                        </p>
                      </td>
                    </tr>
                  </table>
                  <p style="margin:0;color:#4a5568;font-size:14px;line-height:1.6">
                    Please log in and change your password from your <strong>Profile</strong> page.
                  </p>
                </td>
              </tr>
              <tr>
                <td style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:18px 40px">
                  <p style="margin:0;color:#a0aec0;font-size:12px">
                    This is an automated message from {$t}.
                    If you did not expect this email, please contact your administrator.
                  </p>
                </td>
              </tr>
            </table>
          </td></tr>
        </table>
        </body></html>
        HTML;
    }

    private function welcomeEmailText(
        string $fullName,
        string $username,
        string $password,
        string $loginUrl,
        string $siteTitle
    ): string {
        return implode("\n", [
            "Hi {$fullName},",
            '',
            "An admin account has been created for you on {$siteTitle}.",
            "Use the credentials below to sign in.",
            '',
            "Login URL : {$loginUrl}",
            "Username  : {$username}",
            "Password  : {$password}",
            '',
            'Please log in and change your password from your Profile page.',
            '',
            '---',
            "This is an automated message from {$siteTitle}.",
            'If you did not expect this email, please contact your administrator.',
        ]);
    }
}
