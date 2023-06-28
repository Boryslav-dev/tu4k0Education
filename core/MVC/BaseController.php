<?php

declare(strict_types=1);

namespace Framework\MVC;

use Exception;

class BaseController
{
    /**
     * @var \Framework\MVC\Request
     */
    protected Request $request;

    /**
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return void
     */
    public static function pageNotFound(): void
    {
        require_once __DIR__ . "/404.php";
    }

    /**
     * @param string $view
     * @param array|null $params
     * @return void
     * @throws \Exception
     */
    protected function render(string $view, array $params = null): void
    {
        if (is_array($params)) {
            extract($params);
        }
        $path = __DIR__ . "/$view.php";
        if (file_exists($path)) {
            include $path;
        } else {
            throw new Exception('File not exist');
        }
    }
}
