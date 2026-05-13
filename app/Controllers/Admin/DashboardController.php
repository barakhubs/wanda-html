<?php

namespace App\Controllers\Admin;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\HomeGallery;

class DashboardController extends AdminBaseController
{
    public function index(): void
    {
        $blogModel = new BlogPost();

        $counts = [
            'blog'         => $blogModel->count(),
            'portfolio'    => (new Portfolio())->count(),
            'team'         => (new TeamMember())->count(),
            'testimonials' => (new Testimonial())->count(),
        ];

        $this->adminView('admin/dashboard', [
            'adminPageTitle' => 'Dashboard',
            'flash'          => getFlash(),
            'counts'         => $counts,
            'recentPosts'    => $blogModel->getRecent(5),
        ]);
    }
}
