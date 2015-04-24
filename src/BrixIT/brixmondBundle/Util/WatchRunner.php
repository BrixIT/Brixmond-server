<?php

namespace BrixIT\brixmondBundle\Util;


use BrixIT\brixmondBundle\Entity\Client;
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
        foreach ($watches as $watch) {
            $language = new ExpressionLanguage();
            $context = [
                'point' => $datapoint->getPoint(),
                'server' => $client,
                'info' => $info
            ];
            if ($language->evaluate($watch->getExpression(), $context)
            ) {
                if ($watch->getAction() == 'drop') {
                    $results = [];
                    break;
                }

                $watch->setNotificationTitle($this->container->get('twig')->render($watch->getNotificationTitle(), $context));
                $watch->setNotificationMessage($this->container->get('twig')->render($watch->getNotificationMessage(), $context));

                $results[] = $watch;

            }

        }
        foreach ($results as $result) {
            /** @var Watch $result */
            $message = new Message();
            $message->setTitle($result->getNotificationTitle());
            $message->setMessage($result->getNotificationMessage());
            $message->setFixed(false);
            $message->setLevel($result->getAction());
            $message->setClient($client);
            $message->setWatch($result);
            $this->container->get('message_manager')->addMessage($client, $message);
        }


    }
}