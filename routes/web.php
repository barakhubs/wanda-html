<?php

/**
 * routes/web.php — Application route definitions.
 *
 * @var \App\Core\Router $router  Injected by public/index.php
 */

use App\Controllers\AboutController;
use App\Controllers\BlogController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\PortfolioController;
use App\Controllers\ReportController;
use App\Controllers\ServicesController;
use App\Controllers\TeamController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\BlogController      as AdminBlogController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\GalleryController;
use App\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Controllers\Admin\ReportController   as AdminReportController;
use App\Controllers\Admin\ProfileController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\TeamController      as AdminTeamController;
use App\Controllers\Admin\TestimonialController;
use App\Controllers\Admin\UsersController;

// ── Public routes ─────────────────────────────────────────────────────────────
$router->get('/',            [HomeController::class,      'index']);
$router->get('/about',       [AboutController::class,     'index']);
$router->get('/services',    [ServicesController::class,  'index']);
$router->get('/team',        [TeamController::class,      'index']);
$router->get('/portfolio',   [PortfolioController::class, 'index']);
$router->get('/blog',        [BlogController::class,      'index']);
$router->get('/blog/{slug}', [BlogController::class,      'show']);
$router->get('/reports',          [ReportController::class,    'index']);
$router->get('/reports/{slug}',   [ReportController::class,    'show']);
$router->get('/contact',     [ContactController::class,   'index']);

// ── Admin routes ──────────────────────────────────────────────────────────────
$router->get('/admin',         [DashboardController::class, 'index']);
$router->get('/admin/login',   [AuthController::class,      'showLogin']);
$router->post('/admin/login',  [AuthController::class,      'login']);
$router->get('/admin/logout',  [AuthController::class,      'logout']);

// Blog
$router->get('/admin/blog',              [AdminBlogController::class, 'index']);
$router->get('/admin/blog/create',       [AdminBlogController::class, 'create']);
$router->post('/admin/blog/create',      [AdminBlogController::class, 'store']);
$router->get('/admin/blog/edit/{id}',    [AdminBlogController::class, 'edit']);
$router->post('/admin/blog/edit/{id}',   [AdminBlogController::class, 'update']);
$router->post('/admin/blog/delete/{id}', [AdminBlogController::class, 'destroy']);

// Portfolio
$router->get('/admin/portfolio',                  [AdminPortfolioController::class, 'index']);
$router->get('/admin/portfolio/create',           [AdminPortfolioController::class, 'create']);
$router->post('/admin/portfolio/create',          [AdminPortfolioController::class, 'store']);
$router->get('/admin/portfolio/edit/{id}',        [AdminPortfolioController::class, 'edit']);
$router->post('/admin/portfolio/edit/{id}',       [AdminPortfolioController::class, 'update']);
$router->post('/admin/portfolio/delete/{id}',     [AdminPortfolioController::class, 'destroy']);

// Team
$router->get('/admin/team',              [AdminTeamController::class, 'index']);
$router->get('/admin/team/create',       [AdminTeamController::class, 'create']);
$router->post('/admin/team/create',      [AdminTeamController::class, 'store']);
$router->get('/admin/team/edit/{id}',    [AdminTeamController::class, 'edit']);
$router->post('/admin/team/edit/{id}',   [AdminTeamController::class, 'update']);
$router->post('/admin/team/delete/{id}', [AdminTeamController::class, 'destroy']);

// Testimonials
$router->get('/admin/testimonials',                  [TestimonialController::class, 'index']);
$router->get('/admin/testimonials/create',           [TestimonialController::class, 'create']);
$router->post('/admin/testimonials/create',          [TestimonialController::class, 'store']);
$router->get('/admin/testimonials/edit/{id}',        [TestimonialController::class, 'edit']);
$router->post('/admin/testimonials/edit/{id}',       [TestimonialController::class, 'update']);
$router->post('/admin/testimonials/delete/{id}',     [TestimonialController::class, 'destroy']);

// Gallery
$router->get('/admin/gallery',               [GalleryController::class, 'index']);
$router->get('/admin/gallery/create',        [GalleryController::class, 'create']);
$router->post('/admin/gallery/create',       [GalleryController::class, 'store']);
$router->post('/admin/gallery/delete/{id}',  [GalleryController::class, 'destroy']);

// Profile
$router->get('/admin/profile',           [ProfileController::class, 'index']);
$router->post('/admin/profile',          [ProfileController::class, 'update']);
$router->post('/admin/profile/password', [ProfileController::class, 'password']);
$router->post('/admin/profile/username', [ProfileController::class, 'username']);

// Users (admin role only — enforced inside controller)
$router->get('/admin/users',               [UsersController::class, 'index']);
$router->get('/admin/users/create',        [UsersController::class, 'create']);
$router->post('/admin/users/create',       [UsersController::class, 'store']);
$router->get('/admin/users/edit/{id}',     [UsersController::class, 'edit']);
$router->post('/admin/users/edit/{id}',    [UsersController::class, 'update']);
$router->post('/admin/users/delete/{id}',  [UsersController::class, 'destroy']);

// Settings
$router->get('/admin/settings',  [SettingsController::class, 'index']);
$router->post('/admin/settings', [SettingsController::class, 'save']);

// Reports
$router->get('/admin/reports',                [AdminReportController::class, 'index']);
$router->get('/admin/reports/create',         [AdminReportController::class, 'create']);
$router->post('/admin/reports/create',        [AdminReportController::class, 'store']);
$router->get('/admin/reports/edit/{id}',      [AdminReportController::class, 'edit']);
$router->post('/admin/reports/edit/{id}',     [AdminReportController::class, 'update']);
$router->post('/admin/reports/delete/{id}',   [AdminReportController::class, 'destroy']);

// Setup (one-time only — delete after use)
$router->get('/setup',  function () {
    require ROOT_PATH . '/setup.php';
});
$router->post('/setup', function () {
    require ROOT_PATH . '/setup.php';
});
