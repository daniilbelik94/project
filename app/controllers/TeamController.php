<?php

namespace Controllers;

use Core\Controller;
use Core\View;
use User;

class TeamController extends Controller
{
    public function index()
    {
        // Получаем всех пользователей с ролью 'spieler'
        require_once __DIR__ . '/../models/User.php';
        $userModel = new \User();
        $players = $userModel->getAllByRole('spieler');
        $clubInfo = [
            'name' => 'FC Musterstadt',
            'founded' => 1972,
            'description' => 'Der FC Musterstadt ist ein traditionsreicher Amateur-Fußballverein aus Musterstadt. Wir stehen für Teamgeist, Leidenschaft und Nachwuchsförderung.',
            'logo' => 'https://media.teamfanapp.com/clubs/1/logo.png',
        ];
        $this->view('team/index', [
            'players' => $players,
            'clubInfo' => $clubInfo
        ]);
    }
}
