<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

abstract class Controller {
    public function jsonResponse(Response $response, array $data, int $statusCode = 200): Response {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }
}
