<?php

declare(strict_types=1);

namespace Framework\MVC;

class Request
{
    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * @return bool|array
     */
    public function getHeader(): bool|array
    {
        return getallheaders();
    }

    /**
     * @return array
     */
    public function getAllParams(): array
    {
        $parameters = [];
        if ($this->getMethod() === "GET") {
            foreach ($_GET as $key => $value) {
                $parameters[$key] = $value;
            }
        } else {
            foreach ($_POST as $key => $value) {
                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }

    /**
     * @param array|string $params
     * @return array
     */
    public function getParams(array|string $params): array
    {
        $parameters = [];
        if ($this->getMethod() === 'GET') {
            if (is_array($params)) {
                foreach ($params as $value) {
                    $parameters[$value] = $_GET[$value];
                }
            } else {
                $parameters[$params] = $_GET[$params];
            }
        } else {
            if (is_array($params)) {
                foreach ($params as $value) {
                    $parameters[$value] = $_POST[$value];
                }
            } else {
                $parameters[$params] = $_POST[$params];
            }
        }

        return $parameters;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        $body = [];
        if ($this->getMethod() === "POST" || $this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            if (!empty($_POST)) {
                $body = json_decode(file_get_contents('php://input'), true);
            }
        }

        return $body;
    }
}
