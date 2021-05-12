<?php

namespace App\Controller;

use App\Model\MemberManager;

class MemberController extends AbstractController
{
    public function login()
    {
        session_start();

        if (isset($_SESSION["user"])) {
            header('Location: /activity/index');
        }
        $error = '';

        if (!empty($_POST)) {
            $pseudo = $_POST['pseudo'];
            $password = $_POST['password'];
            $memberArray = (new MemberManager())->selectOneByName($pseudo);

            if (password_verify($password, $memberArray['password'])) {
                $_SESSION['user'] = $memberArray;
                header('Location: /activity/index');
            } else {
                $error = 'Identifiants incorrects';
            }
        }

        return $this->twig->render('Member/login.html.twig', [
            'error' => $error,
        ]);
    }

    public function signUp()
    {
        session_start();

        if (isset($_SESSION["user"])) {
            header('Location: /activity/index');
        }
        if (!empty($_POST)) {
            if ($_POST['password'] === $_POST['repeatpassword']) {
                $memberManager = new MemberManager();
                $passwordHashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $memberManager->insert([
                    'name' => trim($_POST['pseudo']),
                    'password' => trim($passwordHashed),
                    'bio' => trim($_POST['bio']),
                    'favorite_activity' => trim($_POST['fav-activity']),

                ]);
                $nameArray = $memberManager->selectOneByName($_POST['pseudo']);
                $nameArray['is_logged'] = true;
                $_SESSION['user'] = $nameArray;
                header('Location: /activity/index');
            } else {
                header('Location: /Home/index');
            }
        }
        return $this->twig->render('Member/signup.html.twig');
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /Home/index');
    }
}
