<?php

namespace App\Controllers;

use App\Models\Portfolio;

class PortfolioController extends BaseController
{
    public function index(): void
    {
        $items = (new Portfolio())->getPublished();

        $categories = array_values(array_unique(array_column($items, 'category')));

        $this->view('portfolio/index', [
            'pageTitle'   => 'Portfolio | Wanda Communications Uganda',
            'pageDesc'    => 'Explore our portfolio of strategic communication, photography, videography, and advocacy work across Uganda and East Africa.',
            'currentPage' => 'portfolio',
            'items'       => $items,
            'categories'  => $categories,
        ]);
    }
}
