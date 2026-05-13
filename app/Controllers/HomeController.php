<?php

namespace App\Controllers;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Testimonial;
use App\Models\HomeGallery;

class HomeController extends BaseController
{
    public function index(): void
    {
        $featuredPosts      = (new BlogPost())->getFeatured(3);
        $featuredPortfolio  = (new Portfolio())->getFeatured(3);
        $testimonials       = (new Testimonial())->getPublished();
        $gallery            = (new HomeGallery())->getPublished();

        $this->view('home/index', [
            'pageTitle'         => setting('site_title', 'Wanda Communications Uganda'),
            'pageDesc'          => setting('site_tagline', 'Your strategic partner in achieving communication excellence.'),
            'currentPage'       => 'home',
            'featuredPosts'     => $featuredPosts,
            'featuredPortfolio' => $featuredPortfolio,
            'testimonials'      => $testimonials,
            'gallery'           => $gallery,
        ]);
    }
}
