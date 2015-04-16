<?php

namespace BrixIT\brixmondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServerPageController extends Controller
{
    protected function getTimeDomainLabel($domain)
    {
        $domains = [
            'hour' => '1 Hour',
            '5min' => '5 Minutes',
            'halfday' => '12 Hours',
            'day' => '24 Hours'
        ];
        return $domains[$domain];
    }

    public function graphsAction($fqdn, $timedomain)
    {
        $context = [
            'fqdn' => $fqdn,
            'timedomain' => $timedomain,
            'timedomainLabel' => $this->getTimeDomainLabel($timedomain)
        ];

        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);

        $context['client'] = $client;

        $infoRows = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:ClientInfo')->findBy([
            'client' => $client
        ]);
        $info = [];
        foreach ($infoRows as $row) {
            $info[$row->getName()] = $row;
        }

        $context['info'] = $info;

        return $this->render('BrixITbrixmondBundle:Default:graphs.html.twig', $context);
    }
}