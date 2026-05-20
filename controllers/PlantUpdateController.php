<?php

class PlantUpdateController extends BasePlantTwigController {
    public $template = "plant_create.twig";
    public function get(array $context){

        $id = $this->params['id'];

        $sql = <<<EOL
SELECT * FROM trees WHERE id = :id
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("id", $id);
        $query->execute();
        $data = $query->fetch();

        $context['object'] = $data;
        $context['isEdit'] = true;

        parent::get($context);

        }

    public function post(array $context) {

        $id = $this->params['id'];

        // получаем значения полей с формы
        $title = $_POST['title'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $info = $_POST['info'];

        if ($_FILES['image']['tmp_name'] != ''){
            $tmp_name = $_FILES['image']['tmp_name'];
            $name =  $_FILES['image']['name'];

            move_uploaded_file($tmp_name, "../public/media/$name");
            $image_url = "/media/$name";
            // создаем текст запрос
            $sql = <<<EOL
UPDATE trees SET title=:title, description=:description, type=:type, info=:info, image=:image_url 
WHERE id=:id
EOL;
        $query = $this->pdo->prepare($sql);
        $query->bindValue("image_url", $image_url);

        }
        else {
            $sql = <<<EOL
UPDATE trees SET title=:title, description=:description, type=:type, info=:info
WHERE id=:id
EOL;
        $query = $this->pdo->prepare($sql);
        }

        // привязываем параметры
        $query->bindValue("id", $id);
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        
        $query->execute();
        
        $context['message'] = 'Вы успешно обновили объект';
        $context['id'] = $id;


        $this->get($context);
    }
}