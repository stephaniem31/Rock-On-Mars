<?php

namespace App\Controller;



class MemberController extends AbstractController
{
    public function signup()
    {
        return $this->twig->render('Member/signup.html.twig');
    }

    public function login()
    {
        return $this->twig->render('Member/login.html.twig');
    }




    
}
