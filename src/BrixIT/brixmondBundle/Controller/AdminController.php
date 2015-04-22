<?php

namespace BrixIT\brixmondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function serversAction()
    {
        return $this->render('BrixITbrixmondBundle:Admin:servers.html.twig');
    }

    public function usersAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        $context = [
            'users' => $users
        ];
        return $this->render('BrixITbrixmondBundle:Admin:users.html.twig', $context);
    }
}