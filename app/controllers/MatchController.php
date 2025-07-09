<?php

namespace Controllers;

use Core\Controller;
use Core\View;
use \MatchModel;

require_once __DIR__ . '/../models/MatchModel.php';

class MatchController extends Controller
{
    public function index()
    {
        $matchModel = new MatchModel();
        $matches = $matchModel->getAll();
        $this->view('match/index', ['matches' => $matches]);
    }
}
