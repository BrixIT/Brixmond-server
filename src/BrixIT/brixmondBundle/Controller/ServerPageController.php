<?php

namespace BrixIT\brixmondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ServerPageController extends Controller
{

    public function chartsAction($fqdn, $timedomain)
    {
        $context = [
            'fqdn' => $fqdn,
            'timedomain' => $timedomain,
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

        return $this->render('BrixITbrixmondBundle:Default:charts.html.twig', $context);
    }

    public function messagesAction($fqdn, $id)
    {
        $context = [
            'fqdn' => $fqdn,
        ];
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);
        $context['client'] = $client;

        $messages = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Message')->findBy([
            'client' => $client
        ]);
        $context['messages'] = $messages;
        if (count($messages) > 0) {
            if ($id == 'top') {
                $message = $messages[0];
            } else {
                $message = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Message')->find($id);
            }
            $context['detail'] = $message;
        }
        return $this->render('BrixITbrixmondBundle:Default:messages.html.twig', $context);
    }
}