<?php

namespace App\Controllers;

class AboutController extends BaseController
{
    public function index(): void
    {
        $this->view('about/index', [
            'pageTitle'   => 'About Us | Wanda Communications Uganda',
            'pageDesc'    => 'About Wanda Communications Uganda — a dynamic, full-service communications agency dedicated to transforming how organizations share their stories.',
            'currentPage' => 'about',
        ]);
    }
}
