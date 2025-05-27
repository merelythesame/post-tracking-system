<?php

namespace middleware;

class BufferingMiddleware implements MiddlewareInterface
{

    public function handle(array $params, callable $next): void
    {
        ob_start();

        $next($params);

        $content = ob_get_contents();
        ob_end_clean();

        $status = http_response_code();

        switch ($status) {
            case 200:
                header('Cache-Control: public, max-age=600');
                break;
            case 404:
            case 500:
                header('Cache-Control: no-store');
                break;
            default:
                header('Cache-Control: no-cache, must-revalidate');
        }

        echo $content;
    }
}