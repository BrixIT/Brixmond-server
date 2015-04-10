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
                'load1' => (float)$point[0],
                'load5' => (float)$point[1],
                'load15' => (float)$point[2],
            ];
        }
        return new JsonResponse($response);
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
                'down' => (int)$point['counters'][$type . '_recv'],
                'up' => (int)$point['counters'][$type . '_sent'],
            ];
        }
        return new JsonResponse($response);
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
        foreach ($datapoints as $datapoint) {
            $point = $datapoint->getPoint();
            $cpuValues = [];
            foreach($point as $cpu){
                $cpuValues[] = $cpu['user'] + $cpu['system'];
            }
            $response[] = [
                'time' => $datapoint->getTime(),
                'cpu' => $cpuValues
            ];
        }
        return new JsonResponse($response);
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
                'dropin' => (int)$point['counters']['dropin'],
                'dropout' => (int)$point['counters']['dropout'],
                'errin' => (int)$point['counters']['errin'],
                'errout' => (int)$point['counters']['errout'],
            ];
        }
        return new JsonResponse($response);
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
                'connecting' => (int)$point['sockets']['connecting'],
                'unknown' => (int)$point['sockets']['unknown'],
                'connected' => (int)$point['sockets']['connected'],
                'closing' => (int)$point['sockets']['closing'],
                'listening' => (int)$point['sockets']['listening'],
            ];
        }
        return new JsonResponse($response);
    }
}