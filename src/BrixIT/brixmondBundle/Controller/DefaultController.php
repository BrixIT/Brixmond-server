<?php

namespace BrixIT\brixmondBundle\Controller;

use BrixIT\brixmondBundle\Entity\Host;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BrixITbrixmondBundle:Default:index.html.twig');
    }

    public function graphAction()
    {
        $translator = $this->get('translator');
        $response = ['digraph {'];
        $response[] = '    rankdir=RL;';
        $response[] = '    node [rx=5 ry=5 labelStyle="font: 300 14px sans-serif"]';
        $response[] = '    edge [labelStyle="font: 300 14px sans-serif"]';
        $response[] = '    internet [label="' . $translator->trans('overview.internet') . '" style="fill: #0099ff;"];';

        $hosts = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Host')->findAll();
        foreach ($hosts as $host) {
            $parameters = [];
            $parameters['label'] = $host->getName();
            switch ($host->getType()) {
                case "vps":
                    $parameters['style'] = 'fill: #ccc;';
                    break;
                case "vm":
                    $parameters['style'] = 'fill: #eee;';
                    break;
                case "hypervisor":
                case "server":
                    $parameters['style'] = 'fill: #ccc;';
                    break;
                case "edgerouter":
                    $parameters['shape'] = 'ellipse';
                    break;

            }
            if ($host->getClient() != null) {
                $parameters['labelType'] = 'html';
                $url = $this->generateUrl('server_charts', ['fqdn' => $host->getClient()->getFQDN()]);
                $parameters['label'] = "<a href='" . $url . "'>" . $host->getName() . "</a>";
            }
            $parameterPart = [];
            foreach ($parameters as $key => $value) {
                $parameterPart[] = $key . '="' . $value . '"';
            }
            $response[] = '    node' . $host->getId() . ' [' . implode(' ', $parameterPart) . ']';
        }

        $rootHosts = $this->getDoctrine()->getRepository('BrixITbrixmondBundle:Host')->findBy([
            'parent' => null
        ]);

        foreach ($rootHosts as $host) {
            $response[] = '    node' . $host->getId() . ' -> internet;';
            $this->getSubrelations($response, $host);
        }

        $response[] = '}';
        return new Response(implode(PHP_EOL, $response));
    }

    private function getSubrelations(&$result, Host $host)
    {
        foreach ($host->getChildren() as $child) {
            /** @var Host $child */
            $result[] = '    node' . $child->getId() . ' -> node' . $host->getId() . ';';
            $this->getSubrelations($result, $child);
        }
        return $result;
    }
}
