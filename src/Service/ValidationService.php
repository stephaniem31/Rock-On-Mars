<?php

namespace App\Service;

class ValidationService
{

    public function checkAddFormEmptiness()
    {
        if (
            empty($_POST['title']) || empty($_POST['activity_type'])
            || empty($_POST['content']) || empty($_POST['max_registered_members'])
            || empty($_POST['localisation']) || empty($_POST['start_at'])
            || empty($_POST['end_at'])
        ) {
            $errors = 'Veuillez remplir tout les champs.';
            return $errors;
        }
        return null;
    }
}
