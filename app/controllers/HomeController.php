<?php

namespace Controllers;

use Core\Controller;
use Core\View;
use User;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home/index', ['title' => 'Willkommen beim FC Musterstadt']);
    }
}
