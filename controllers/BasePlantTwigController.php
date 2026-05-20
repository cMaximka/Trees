<?php

class BasePlantTwigController extends TwigBaseController {
    public function getContext(): array
    {
        $query = $this->pdo->query("SELECT id, title FROM trees_types ORDER BY 1");
        $types = $query->fetchAll();
        $context["types"] = $types;
        
        $context["history"] = isset($_SESSION['history']) ? $_SESSION['history'] : [];
        
        return $context;
    }

}