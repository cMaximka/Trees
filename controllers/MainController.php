<?php
require_once "BasePlantTwigController.php";

class MainController extends BasePlantTwigController {


    public $template = "main.twig";
    public $title = "Главная";

    public function getContext(): array
    {
        $context = parent::getContext();
        $query = $this->pdo->query("SELECT * FROM trees");

        if (isset($_GET['type'])) {
            $query = $this->pdo->prepare("SELECT trees.* FROM trees JOIN trees_types ON trees.type = trees_types.id WHERE trees_types.title = :type");
            $query->bindValue("type", $_GET['type']);
            $query->execute();
        }

        $context['plants'] = $query->fetchAll();

        return $context;
    }
}