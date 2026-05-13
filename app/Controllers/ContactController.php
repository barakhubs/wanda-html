<?php

namespace App\Controllers;

class ContactController extends BaseController
{
    public function index(): void
    {
        $this->view('contact/index', [
            'pageTitle'   => 'Contact Us | Wanda Communications Uganda',
            'pageDesc'    => "Get in touch with Wanda Communications Uganda. We'd love to hear about your communication needs.",
            'currentPage' => 'contact',
        ]);
    }
}
