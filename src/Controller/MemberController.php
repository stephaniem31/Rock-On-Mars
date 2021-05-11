<?php

namespace App\Controller;


use App\Model\MemberManager;


class MemberController extends AbstractController
{
    public function login()

    {
        if (!empty($_POST)) {
            $pseudo = $_POST['pseudo'];
            $password = $_POST['password'];
            $memberManager = new MemberManager();
            $nameArray = $memberManager->selectAll($pseudo);

            if (
                !empty($nameArray) &&
                $pseudo === $nameArray['pseudo'] &&
                password_verify($password, $nameArray['mot_de_passe'])
            ) {
                $nameArray['est_connecte'] = true;
                $_SESSION['member'] = $nameArray;
                header('Location: /Activity/index');
            } else {
                $error = 'Identifiants incorrects';
            }
        }

        return $this->twig->render('home/index.html.twig', [
            'error' => $error,
        ]);

        return $this->twig->render('Member/signup.html.twig');
    }

    public function signUp()
    {
        if ($_POST['password'] === $_POST['repeatpassword']) {
            $MemberManager = new MemberManager();
            $passwordHashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $MemberManager->insert([
                'pseudo' => trim($_POST['pseudo']),
                'password' => trim($passwordHashed),
            ]);
            $nameArray = $MemberManager->selectAll($_POST['pseudo']);
            $nameArray['est_connecte'] = true;
            $_SESSION['user'] = $nameArray;
            header('Location: /Activity/index');
        } else {
            header('Location: /Home/index');
        }
    }
}
