<?php

namespace BrixIT\brixmondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function configAction($fqdn, $secret)
    {
        return $this->render('');
    }

    public function packetAction($fqdn, $secret)
    {
        return new Response();
    }
}