<?php

namespace App\Controllers;

use App\Models\TeamMember;

class TeamController extends BaseController
{
    public function index(): void
    {
        $members = (new TeamMember())->getAll();

        $this->view('team/index', [
            'pageTitle'   => 'Our Team | Wanda Communications Uganda',
            'pageDesc'    => 'Meet the talented team behind Wanda Communications Uganda — experienced professionals dedicated to strategic communication excellence.',
            'currentPage' => 'team',
            'members'     => $members,
        ]);
    }
}
