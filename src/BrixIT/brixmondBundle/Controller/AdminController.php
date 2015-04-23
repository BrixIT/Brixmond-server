<?php

namespace BrixIT\brixmondBundle\Controller;

use BrixIT\brixmondBundle\Entity\Host;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function serversAction()
    {
        $hostsActive = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findBy([
            'enabled' => true
        ]);
        $hostsInactive = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findBy([
            'enabled' => false
        ]);
        $context = [
            'clients' => [
                'active' => $hostsActive,
                'inactive' => $hostsInactive
            ]
        ];
        return $this->render('BrixITbrixmondBundle:Admin:servers.html.twig', $context);
    }

    public function serverAcceptAction($id)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->find($id);
        $client->setEnabled(true);
        $manager = $this->getDoctrine()->getManager();

        $host = new Host();
        $host->setClient($client);
        $host->setName($client->getFqdn());
        $host->setHostname($client->getFqdn());
        $host->setPing(0);
        $host->setState(true);
        $host->setType('server');

        $manager->persist($client);
        $manager->persist($host);
        $manager->flush();
        return $this->redirectToRoute('admin_servers', [], 303);
    }

    public function serverRejectAction($id)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($client);
        $manager->flush();
        return $this->redirectToRoute('admin_servers', [], 303);
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