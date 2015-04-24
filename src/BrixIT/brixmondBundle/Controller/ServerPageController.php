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

        $messages = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Message')->findBy([
            'client' => $client,
            'fixed' => false
        ]);

        $context['messagecount'] = count($messages);

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

    public function acknowledgeMessageAction($fqdn, $id)
    {
        $message = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Message')->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();

        $message->setAcknowledged($user);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($message);
        $manager->flush();
        //TODO: Notify users that someone assigned the message to him.
        return $this->redirectToRoute('server_message_detail', ['fqdn' => $fqdn, 'id' => $id], 303);
    }

    public function fixMessageAction($fqdn, $id)
    {
        $message = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Message')->find($id);
        $message->setFixed(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($message);
        $manager->flush();
        //TODO: Notify users that the issue is fixed.
        return $this->redirectToRoute('server_message_detail', ['fqdn' => $fqdn, 'id' => $id], 303);
    }
}