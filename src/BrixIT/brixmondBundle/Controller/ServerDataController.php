<?php

namespace BrixIT\brixmondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ServerDataController extends Controller
{
    protected function getTimeDomain($label)
    {
        $domains = [
            'hour' => '1 hours',
            '5min' => '5 minutes',
            'halfday' => '12 hours',
            'day' => '24 hours'
        ];
        return $domains[$label];
    }

    public function systemLoadAction($fqdn, $timedomain)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);
        $repository = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Datapoint');
        $query = $repository->createQueryBuilder('d')
            ->where('d.client = :client')
            ->andWhere('d.system = :system')
            ->andWhere('d.time >= :begin')
            ->setParameter('client', $client)
            ->setParameter('system', 'load')
            ->setParameter('begin', new \DateTime('-' . $this->getTimeDomain($timedomain), new \DateTimeZone('UTC')))
            ->orderBy('d.time', 'ASC')
            ->getQuery();
        $datapoints = $query->getResult();
        $response = [];
        foreach ($datapoints as $datapoint) {
            $point = $datapoint->getPoint();
            $response[] = [
                'time' => $datapoint->getTime(),
                'series' => [
                    (float)$point[0],
                    (float)$point[1],
                    (float)$point[2]
                ]
            ];
        }
        return new JsonResponse([
            'dataset' => $response,
            'labels' => ['1 Minute load', '5 Minute load', '15 Minute load'],
            'minimalHeight' => 1
        ]);
    }

    public function networkBytesAction($fqdn, $timedomain, $type)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);
        $repository = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Datapoint');
        $query = $repository->createQueryBuilder('d')
            ->where('d.client = :client')
            ->andWhere('d.system = :system')
            ->andWhere('d.time >= :begin')
            ->setParameter('client', $client)
            ->setParameter('system', 'net')
            ->setParameter('begin', new \DateTime('-' . $this->getTimeDomain($timedomain), new \DateTimeZone('UTC')))
            ->orderBy('d.time', 'ASC')
            ->getQuery();
        $datapoints = $query->getResult();
        $response = [];
        foreach ($datapoints as $datapoint) {
            $point = $datapoint->getPoint();
            $response[] = [
                'time' => $datapoint->getTime(),
                'series' => [
                    (int)$point['counters'][$type . '_recv'],
                    (int)$point['counters'][$type . '_sent']
                ]
            ];
        }
        return new JsonResponse([
            'dataset' => $response,
            'labels' => ['Download', 'Upload'],
            'minimalHeight' => 10
        ]);
    }

    public function cpuUsageAction($fqdn, $timedomain)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);
        $repository = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Datapoint');
        $query = $repository->createQueryBuilder('d')
            ->where('d.client = :client')
            ->andWhere('d.system = :system')
            ->andWhere('d.time >= :begin')
            ->setParameter('client', $client)
            ->setParameter('system', 'cpu')
            ->setParameter('begin', new \DateTime('-' . $this->getTimeDomain($timedomain), new \DateTimeZone('UTC')))
            ->orderBy('d.time', 'ASC')
            ->getQuery();
        $datapoints = $query->getResult();
        $response = [];
        $cpuCount = 0;
        foreach ($datapoints as $datapoint) {
            $point = $datapoint->getPoint();
            $cpuValues = [];
            foreach($point as $cpu){
                $cpuValues[] = $cpu['user'] + $cpu['system'];
            }
            $cpuCount = count($cpuValues);
            $response[] = [
                'time' => $datapoint->getTime(),
                'series' => $cpuValues
            ];
        }
        $labels = [];
        for($i=0;$i<$cpuCount;$i++){
            $labels[] = 'Core ' . ($i+1);
        }
        return new JsonResponse([
            'dataset' => $response,
            'labels' => $labels,
            'minimalHeight' => 100
        ]);
    }

    public function networkErrorAction($fqdn, $timedomain)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);
        $repository = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Datapoint');
        $query = $repository->createQueryBuilder('d')
            ->where('d.client = :client')
            ->andWhere('d.system = :system')
            ->andWhere('d.time >= :begin')
            ->setParameter('client', $client)
            ->setParameter('system', 'net')
            ->setParameter('begin', new \DateTime('-' . $this->getTimeDomain($timedomain), new \DateTimeZone('UTC')))
            ->orderBy('d.time', 'ASC')
            ->getQuery();
        $datapoints = $query->getResult();
        $response = [];
        foreach ($datapoints as $datapoint) {
            $point = $datapoint->getPoint();
            $response[] = [
                'time' => $datapoint->getTime(),
                'series' => [
                    (int)$point['counters']['dropin'],
                    (int)$point['counters']['dropout'],
                    (int)$point['counters']['errin'],
                    (int)$point['counters']['errout']
                ]
            ];
        }
        return new JsonResponse([
            'dataset' => $response,
            'labels' => ['Drop in', 'Drop out', 'Error in', 'Error out'],
            'minimalHeight' => 10
        ]);
    }

    public function socketsAction($fqdn, $timedomain)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);
        $repository = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Datapoint');
        $query = $repository->createQueryBuilder('d')
            ->where('d.client = :client')
            ->andWhere('d.system = :system')
            ->andWhere('d.time >= :begin')
            ->setParameter('client', $client)
            ->setParameter('system', 'net')
            ->setParameter('begin', new \DateTime('-' . $this->getTimeDomain($timedomain), new \DateTimeZone('UTC')))
            ->orderBy('d.time', 'ASC')
            ->getQuery();
        $datapoints = $query->getResult();
        $response = [];
        foreach ($datapoints as $datapoint) {
            $point = $datapoint->getPoint();
            $response[] = [
                'time' => $datapoint->getTime(),
                'series' => [
                    (int)$point['sockets']['connecting'],
                    (int)$point['sockets']['connected'],
                    (int)$point['sockets']['closing'],
                    (int)$point['sockets']['listening'],
                    (int)$point['sockets']['unknown']
                ]
            ];
        }
        return new JsonResponse([
            'dataset' => $response,
            'labels' => ['Connecting', 'Connected', 'Closing', 'Listening', 'Unknown'],
            'minimalHeight' => 10
        ]);
    }

    public function memoryAction($fqdn, $timedomain)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);
        $repository = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Datapoint');
        $query = $repository->createQueryBuilder('d')
            ->where('d.client = :client')
            ->andWhere('d.system = :system')
            ->andWhere('d.time >= :begin')
            ->setParameter('client', $client)
            ->setParameter('system', 'mem')
            ->setParameter('begin', new \DateTime('-' . $this->getTimeDomain($timedomain), new \DateTimeZone('UTC')))
            ->orderBy('d.time', 'ASC')
            ->getQuery();
        $datapoints = $query->getResult();
        $response = [];
        foreach ($datapoints as $datapoint) {
            $point = $datapoint->getPoint();
            $response[] = [
                'time' => $datapoint->getTime(),
                'series' => [
                    (int)$point['mem']['total'] - (int)$point['mem']['available'],
                    (int)$point['mem']['buffers'],
                    (int)$point['mem']['cached'],
                    (int)$point['mem']['free']
                ]
            ];
        }
        return new JsonResponse([
            'dataset' => $response,
            'labels' => ['Used', 'Buffers', 'Cache', 'Free'],
            'minimalHeight' => 10
        ]);
    }

    public function swapAction($fqdn, $timedomain)
    {
        $client = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Client')->findOneBy([
            'fqdn' => $fqdn
        ]);
        $repository = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Datapoint');
        $query = $repository->createQueryBuilder('d')
            ->where('d.client = :client')
            ->andWhere('d.system = :system')
            ->andWhere('d.time >= :begin')
            ->setParameter('client', $client)
            ->setParameter('system', 'mem')
            ->setParameter('begin', new \DateTime('-' . $this->getTimeDomain($timedomain), new \DateTimeZone('UTC')))
            ->orderBy('d.time', 'ASC')
            ->getQuery();
        $datapoints = $query->getResult();
        $response = [];
        foreach ($datapoints as $datapoint) {
            $point = $datapoint->getPoint();
            $response[] = [
                'time' => $datapoint->getTime(),
                'series' => [
                    (int)$point['swap']['used'],
                    (int)$point['swap']['free'],
                ]
            ];
        }
        return new JsonResponse([
            'dataset' => $response,
            'labels' => ['Used', 'Free'],
            'minimalHeight' => 1024
        ]);
    }

}