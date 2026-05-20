<?php

class Controller
{
    protected function render(string $view, array $params = []): void
    {
        extract($params, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }
}
