<?php

class SetWelcomeController extends BaseController {
    public function get(array $context) {
        $_SESSION['welcome_message'] = $_GET['message'];

        // проверяем существует ли значение messages в списке
        if (!isset($_SESSION['messages'])) {
            // если пустое, инициализируем его пустым списком
            $_SESSION['messages'] = [];
        }

        // добавляем в список сообщений, введенное сообщение
        array_push($_SESSION['messages'], $_GET['message']);

        $url = $_SERVER['HTTP_REFERER'];
        header("Location: $url");
        exit;
    }
}