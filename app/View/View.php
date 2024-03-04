<?php

namespace App\View;

class View
{
    private array $vars = [];
    const TEMPLATES = 'app/View/Templates/';
    
    public function render(): string
    {
        ob_start();
        extract($this->vars, EXTR_SKIP);

        $path = self::TEMPLATES . 'Page.tpl.php';

        if (!file_exists($path)) {
            exit(__METHOD__ . ' Please define corresponding page for the error code issued');
        }
        include $path;

        return ob_get_clean();
    }

    // Drawing error page based on http error code
    public function error(int $code): void
    {
        http_response_code($code);
        $path = self::TEMPLATES . $code. '.tpl.php';

        if (!file_exists($path)) {
            exit(__METHOD__ . ' Please define corresponding page for the error code issued');
        }
        include $path;
    }

    public function addVar(string $name, string $data): void
    {
        $this->vars[$name] = $data;
    }
}
