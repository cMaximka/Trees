<?php
require_once "BasePlantTwigController.php";

class LoginController extends BasePlantTwigController {
    public $template = "login.twig";
    public $title = "Вход";

    public function get(array $context) {
        parent::get($context);
    }

    public function post(array $context) {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $sql = <<<EOL
SELECT id, username, password
FROM users
WHERE username = :username
EOL;
        $query = $this->pdo->prepare($sql);
        $query->bindValue(':username', $username);
        $query->execute();

        $data = $query->fetch();

        if ($data && $password === $data['password']) {
            $_SESSION['is_logged'] = true;
            header("Location: /");
            exit;
        } else {
            $context['error'] = 'Неверное имя пользователя или пароль';
            parent::get($context);
        }
    }
}
