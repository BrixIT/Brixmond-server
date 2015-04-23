<?php

namespace BrixIT\brixmondBundle\Controller;

use BrixIT\brixmondBundle\Entity\Host;
use BrixIT\brixmondBundle\Form\HostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    public function hostsAction()
    {
        $hosts = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Host')->findAll();

        $context = [
            'hosts' => $hosts
        ];
        return $this->render('BrixITbrixmondBundle:Admin:hosts.html.twig', $context);
    }

    public function hostEditAction(Request $request, $id)
    {
        if ($id === 'new') {
            $host = new Host();
        } else {
            $host = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Host')->find($id);
        }
        $form = $this->createForm(new HostType(), $host);
        $form->add('save', 'submit', array('label' => 'admin.hosts.form.save.label'));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($host);
            $manager->flush();
            return $this->redirectToRoute('admin_hosts', [], 303);
        }
        $context = [
            'form' => $form->createView()
        ];
        return $this->render('BrixITbrixmondBundle:Admin:host_edit.html.twig', $context);
    }
}