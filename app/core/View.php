<?php

namespace Core;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View '$view' nicht gefunden.";
        }
    }
}
