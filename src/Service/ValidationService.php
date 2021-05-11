<?php

namespace App\Service;

class ValidationService
{

    public function checkAddImage()
    {
        $arrExtensionsOK = ['jpg','svg','png', 'jpeg'];
        $extension = (pathinfo($_POST['image'], PATHINFO_EXTENSION));

        if (!in_array($extension, $arrExtensionsOK)) {
            $errors = "L'url doit amener sur un fichier png, jpg, jpeg ou svg.";
            return $errors;
        }
        return null;
    }

    public function checkAddFormEmptiness()
    {
        if (
            empty($_POST['title']) || empty($_POST['activity_type'])
            || empty($_POST['content']) || empty($_POST['max_registered_members'])
            || empty($_POST['localisation']) || empty($_POST['start_at'])
            || empty($_POST['end_at']) || empty($_POST['image'])
        ) {
            $errors = 'Veuillez remplir tout les champs.';
            return $errors;
        }
        return null;
    }
}
