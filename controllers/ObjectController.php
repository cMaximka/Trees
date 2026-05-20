<?php


class ObjectController extends BasePlantTwigController {
    public $template = "object.twig";

    public function getContext(): array
    {
        $context = parent::getContext();
        
        $query = $this->pdo->prepare("SELECT image, info, description, id FROM trees WHERE id= :my_id");
        $query->bindValue("my_id", $this->params['id']);
        $query->execute();

        $data = $query->fetch();
        
        $context['description'] = $data['description'];
        $context['id'] = $data['id'];
        $context['is_image'] = false;
        $context['is_info'] = false;

        if (isset($_GET['show'])) {
            $show = $_GET['show'];
            if ($show === 'image') {     
                $context['image'] = $data['image'];
                $context['is_image'] = true;
                $this->template = "object_image.twig";
            } elseif ($show === 'info') {  
                $context['info'] = $data['info'];
                $context['is_info'] = true;
                $this->template = "object_info.twig";
            }
        } 

        $context["my_session_message"] = isset($_SESSION['welcome_message']) ? $_SESSION['welcome_message'] : "";
        $context["messages"] = isset($_SESSION['messages']) ? $_SESSION['messages'] : "";

        return $context;
    }
}