<?php

namespace BrixIT\brixmondBundle\Controller;

use BrixIT\brixmondBundle\Entity\Client;
use BrixIT\brixmondBundle\Entity\Datapoint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function configAction(Request $request, $fqdn, $secret)
    {
        $arch = $request->query->get('arch', 'i386');
        $distro = $request->query->get('dist', 'unknown');
        $cpu = $request->query->get('cpu', 'unknown');

        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn,
            'secret' => $secret
        ]);
        if (!$client) {
            $client = new Client();
            $client->setFqdn($fqdn);
            $client->setSecret($secret);
            $client->setEnabled(false);
            $client->setSendThrottle(120);
            $client->setCpu($cpu);
            $client->setDist($distro);
            $client->setArch($arch);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($client);
            $manager->flush();
            return new JsonResponse([
                'enabled' => false,
                'polling_time' => 1 // minutes
            ]);
        } else {
            if ($client->getEnabled()) {
                return new JsonResponse([
                    'enabled' => true,
                    'send_throttle' => $client->getSendThrottle()
                ]);
            } else {
                return new JsonResponse([
                    'enabled' => false,
                    'polling_time' => 60 //minutes
                ]);
            }
        }
    }

    public function packetAction(Request $request, $fqdn, $secret)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn,
            'secret' => $secret
        ]);

        if(!$client){
            return new JsonResponse(["error" => "Client not accepted"], 412);
        }

        $manager = $this->getDoctrine()->getManager();
        $packets = json_decode($request->getContent(), true);
        foreach ($packets as $packet) {
            $datapoint = new Datapoint();
            $datapoint->setClient($client);
            $datapoint->setSystem($packet['name']);
            $datapoint->setPoint($packet['point']);
            $datapoint->setTime(new \DateTime($packet['stamp']));
            $manager->persist($datapoint);
        }
        $manager->flush();
        return new Response();
    }
}