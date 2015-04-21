<?php

namespace BrixIT\brixmondBundle\Controller;

use BrixIT\brixmondBundle\Entity\Client;
use BrixIT\brixmondBundle\Entity\ClientInfo;
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
                    'send_throttle' => $client->getSendThrottle(),
                    'monitor_enabled' => [ // TODO: Retrieve this config from database
                        'apache' => false
                    ]
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

        if (!$client) {
            return new JsonResponse(["error" => "Client not accepted"], 412);
        }

        $manager = $this->getDoctrine()->getManager();
        $packets = json_decode($request->getContent(), true);

        $existingInfoRows = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:ClientInfo')->findBy([
            'client' => $client
        ]);
        $existingInfo = [];
        foreach ($existingInfoRows as $row) {
            $existingInfo[$row->getName()] = $row;
        }

        foreach ($packets as $packet) {
            if ($packet['type'] == 'point') {
                $datapoint = new Datapoint();
                $datapoint->setClient($client);
                $datapoint->setSystem($packet['name']);
                $datapoint->setPoint(json_decode($packet['point'], true));
                $datapoint->setTime(new \DateTime($packet['stamp']));
                $manager->persist($datapoint);
            } elseif ($packet['type'] == 'info') {
                if (array_key_exists($packet['name'], $existingInfo)) {
                    $existingInfo[$packet['name']]->setValue(json_decode($packet['point'], true));
                    $manager->persist($existingInfo[$packet['name']]);
                } else {
                    $info = new ClientInfo();
                    $info->setClient($client);
                    $info->setName($packet['name']);
                    $info->setValue(json_decode($packet['point'], true));
                    $manager->persist($info);
                    $existingInfo[$packet['name']] = $info;
                }
            }

        }
        $manager->flush();
        return new Response();
    }
}