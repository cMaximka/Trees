<?php

class HistoryMiddleware extends BaseMiddleware {
    public function apply(BaseController $controller, array $context)
    {
        if (!isset($_SESSION['history'])) {
            $_SESSION['history'] = [];
        }

        $currentUrl = urldecode($_SERVER['REQUEST_URI']);
        $lastUrl = end($_SESSION['history']); 

        if ($lastUrl === false || $lastUrl !== $currentUrl) {
            array_push($_SESSION['history'], $currentUrl);

            if (count($_SESSION['history']) > 10) {
                array_shift($_SESSION['history']);
            }
        }
    }
}
