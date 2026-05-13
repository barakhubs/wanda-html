<?php

namespace App\Controllers;

class ServicesController extends BaseController
{
    public function index(): void
    {
        $this->view('services/index', [
            'pageTitle'   => 'Our Services | Wanda Communications Uganda',
            'pageDesc'    => 'Explore our comprehensive range of communication services — from strategic plans and advocacy campaigns to photography, videography, and content creation.',
            'currentPage' => 'services',
        ]);
    }
}
