<?php
require_once "BasePlantTwigController.php";

class SearchController extends BasePlantTwigController {
    public $template = "search.twig";

    public function getContext(): array
    {
        $context = parent::getContext();

        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $title = isset($_GET['title']) ? $_GET['title'] : '';
        $description = isset($_GET['description']) ? $_GET['description'] : '';

        $sql = <<<EOL
SELECT trees.id, trees.title
FROM trees JOIN trees_types ON trees.type = trees_types.id 
WHERE (:title = '' OR trees.title LIKE CONCAT('%', :title, '%'))
AND (:type = '' OR trees_types.title = :type)
AND (:description = '' OR description LIKE CONCAT(:description))
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue('title', $title);
        $query->bindValue('type', $type);
        $query->bindValue('description', $description);
        $query->execute();
        
        $context['type'] = $type;
        $context['title'] = $title;
        $context['description'] = $description;

        $context['objects'] = $query->fetchAll();

        return $context;
    }
}