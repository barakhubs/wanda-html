<?php

namespace App\Controllers\Admin;

use App\Models\SiteSettings;

class SettingsController extends AdminBaseController
{
    public function index(): void
    {
        \SiteSettings::ensureTable(\Database::getInstance());

        $this->adminView('admin/settings/index', [
            'adminPageTitle' => 'Site Settings',
            'flash'          => getFlash(),
            'errors'         => [],
            's'              => (new SiteSettings())->getAll(),
        ]);
    }

    public function save(): void
    {
        verifyCsrf();

        \SiteSettings::ensureTable(\Database::getInstance());

        $settings = new SiteSettings();
        $errors   = [];

        $data = [
            'site_title'           => trim($_POST['site_title']           ?? ''),
            'site_tagline'         => trim($_POST['site_tagline']         ?? ''),
            'contact_address'      => trim($_POST['contact_address']      ?? ''),
            'contact_email'        => trim($_POST['contact_email']        ?? ''),
            'contact_phone_1'      => trim($_POST['contact_phone_1']      ?? ''),
            'contact_phone_2'      => trim($_POST['contact_phone_2']      ?? ''),
            'contact_hours'        => trim($_POST['contact_hours']        ?? ''),
            'social_facebook'      => trim($_POST['social_facebook']      ?? ''),
            'social_twitter'       => trim($_POST['social_twitter']       ?? ''),
            'social_instagram'     => trim($_POST['social_instagram']     ?? ''),
            'social_linkedin'      => trim($_POST['social_linkedin']      ?? ''),
            'social_youtube'       => trim($_POST['social_youtube']       ?? ''),
            'form_recipient_email' => trim($_POST['form_recipient_email'] ?? ''),
            'smtp_host'            => trim($_POST['smtp_host']            ?? ''),
            'smtp_port'            => trim($_POST['smtp_port']            ?? '587'),
            'smtp_encryption'      => in_array($_POST['smtp_encryption'] ?? '', ['tls', 'ssl', 'none'])
                ? $_POST['smtp_encryption'] : 'tls',
            'smtp_from_name'       => trim($_POST['smtp_from_name']       ?? ''),
            'smtp_from_email'      => trim($_POST['smtp_from_email']      ?? ''),
            'smtp_username'        => trim($_POST['smtp_username']        ?? ''),
        ];

        $smtpPw = $_POST['smtp_password'] ?? '';
        if ($smtpPw !== '') {
            $data['smtp_password'] = $smtpPw;
        }

        foreach (['logo' => 'logo_path', 'footer_logo' => 'footer_logo_path'] as $field => $key) {
            if (!empty($_FILES[$field]['name'])) {
                try {
                    $data[$key] = handleUpload($_FILES[$field], 'logos');
                } catch (\RuntimeException $e) {
                    $errors[] = ($field === 'logo' ? 'Header Logo' : 'Footer Logo') . ': ' . $e->getMessage();
                }
            }
        }

        if (!empty($errors)) {
            $this->adminView('admin/settings/index', [
                'adminPageTitle' => 'Site Settings',
                'flash'          => null,
                'errors'         => $errors,
                's'              => array_merge($settings->getAll(), $data),
            ]);
            return;
        }

        $settings->setMany($data);
        flashMessage('success', 'Settings saved successfully.');
        $this->redirect(BASE_URL . '/admin/settings');
    }
}
