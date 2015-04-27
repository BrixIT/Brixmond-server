<?php

namespace BrixIT\brixmondBundle\Util;


use BrixIT\brixmondBundle\Entity\Client;
use BrixIT\brixmondBundle\Entity\ClientInfo;
use BrixIT\brixmondBundle\Entity\Datapoint;
use BrixIT\brixmondBundle\Entity\Message;
use BrixIT\brixmondBundle\Entity\Watch;
use BrixIT\brixmondBundle\Model\UserNotification;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class WatchRunner extends ContainerAware
{
    public function runPointWatches(Datapoint $datapoint, Client $client)
    {
        $watches = $this->container->get('doctrine')->getRepository('BrixITbrixmondBundle:Watch')->findBy([
            'type' => 'point',
            'system' => $datapoint->getSystem()
        ]);
        $info = [];
        foreach ($client->getInfo() as $i) {
            $info[$i->getName()] = $i;
        }
        $results = [];
        $previousDatapoint = $this->container->get('doctrine')->getRepository('BrixITbrixmondBundle:Datapoint')->findBy([
            'client' => $client,
            'system' => $datapoint->getSystem()
        ], [
            'time' => 'DESC'
        ], 1, 1);
        $previousDatapoint = $previousDatapoint[0];
        if ($previousDatapoint == null) {
            $previousDatapoint = $datapoint;
        }
        foreach ($watches as $watch) {
            $language = new ExpressionLanguage();
            $context = [
                'point' => $datapoint->getPoint(),
                'previousPoint' => $previousDatapoint->getPoint(),
                'server' => $client,
                'info' => $info
            ];
            if ($watch->getDebug()) {
                $this->saveDebugContext($context, $watch);
            }
            if ($language->evaluate($watch->getExpression(), $context)) {
                if ($watch->getAction() == 'drop') {
                    $results = [];
                    break;
                }

                $watch->setNotificationTitleRendered($this->container->get('twig')->render($watch->getNotificationTitle(), $context));
                $watch->setNotificationMessageRendered($this->container->get('twig')->render($watch->getNotificationMessage(), $context));

                $results[] = $watch;

            }

        }
        foreach ($results as $result) {
            /** @var Watch $result */
            $message = new Message();
            $message->setTitle($result->getNotificationTitleRendered());
            $message->setMessage($result->getNotificationMessageRendered());
            $message->setFixed(false);
            $message->setLevel($result->getAction());
            $message->setClient($client);
            $message->setWatch($result);
            $message->setExtra($client->getInfo()->getValues());
            $this->container->get('message_manager')->addMessage($client, $message);
        }


    }

    public function runInfoWatches(Array $infoPacket, ClientInfo $previousInfoPacket, Client $client)
    {
        $watches = $this->container->get('doctrine')->getRepository('BrixITbrixmondBundle:Watch')->findBy([
            'type' => 'info',
            'system' => $previousInfoPacket->getName()
        ]);
        $info = [];
        foreach ($client->getInfo() as $i) {
            $info[$i->getName()] = $i;
        }
        $results = [];
        foreach ($watches as $watch) {
            $language = new ExpressionLanguage();
            $context = [
                'point' => $infoPacket,
                'previousPoint' => $previousInfoPacket->getValue(),
                'server' => $client,
                'info' => $info
            ];
            if ($watch->getDebug()) {
                $this->saveDebugContext($context, $watch);
            }
            if ($language->evaluate($watch->getExpression(), $context)) {
                if ($watch->getAction() == 'drop') {
                    $results = [];
                    break;
                }

                $watch->setNotificationTitleRendered($this->container->get('twig')->render($watch->getNotificationTitle(), $context));
                $watch->setNotificationMessageRendered($this->container->get('twig')->render($watch->getNotificationMessage(), $context));

                $results[] = $watch;

            }

        }
        foreach ($results as $result) {
            /** @var Watch $result */
            $message = new Message();
            $message->setTitle($result->getNotificationTitleRendered());
            $message->setMessage($result->getNotificationMessageRendered());
            $message->setFixed(false);
            $message->setLevel($result->getAction());
            $message->setClient($client);
            $message->setWatch($result);
            $message->setExtra($client->getInfo()->getValues());
            $this->container->get('message_manager')->addMessage($client, $message);
        }


    }

    private function saveDebugContext(array $context, Watch $watch)
    {
        $watch->setDebugValue($context);
        $manager = $this->container->get('doctrine')->getManager();
        $manager->persist($watch);
        $manager->flush();
    }
}