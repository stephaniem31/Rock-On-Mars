<?php

namespace App\Controller;


use App\Model\MemberManager;


class MemberController extends AbstractController
{
    public function login()
    {
        if (isset($_SESSION["user"])) {
            header('Location: /activity/index');
        }
        session_start();
        $error = '';

        if (!empty($_POST)) {
            $pseudo = $_POST['pseudo'];
            $password = $_POST['password'];
            $memberArray = (new MemberManager())->selectOneByName($pseudo);
            

            if (
                !empty($_POST) &&
                password_verify($password, $memberArray['password'])
            ) {
                $_SESSION['user'] = $memberArray;
                header('Location: /home/index');
            } else {
                $error = 'Identifiants incorrects';
            }
        }

        return $this->twig->render('member/login.html.twig', [
            'error' => $error,
        ]);

        return $this->twig->render('Member/login.html.twig');
    }

    public function signUp()
    {
        if (!empty($_POST))
        {
            if ($_POST['password'] === $_POST['repeatpassword']) {
                $MemberManager = new MemberManager();
                $passwordHashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $MemberManager->insert([
                    'name' => trim($_POST['pseudo']),
                    'password' => trim($passwordHashed),
                    'bio' => trim($_POST['bio']),
                    'favorite_activity' => trim($_POST['fav-activity']),
                    
                ]);
                $nameArray = $MemberManager->selectOneByName($_POST['pseudo']);
                $nameArray['is_logged'] = true;
                $_SESSION['user'] = $nameArray;
                header('Location: /home/index');
            } else {
                header('Location: /Home/index');
            }
        }
        return $this->twig->render('Member/signUp.html.twig');
    }
}
