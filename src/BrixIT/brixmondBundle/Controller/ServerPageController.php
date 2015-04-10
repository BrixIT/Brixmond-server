<?php

namespace BrixIT\brixmondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServerPageController extends Controller
{
    public function graphsAction($fqdn, $timedomain)
    {
        $context = [
            'fqdn' => $fqdn,
            'timedomain' => $timedomain
        ];

        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);

        $context['client'] = $client;

        return $this->render('BrixITbrixmondBundle:Default:graphs.html.twig', $context);
    }
}