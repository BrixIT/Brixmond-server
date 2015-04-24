<?php

namespace BrixIT\brixmondBundle\Util;


use BrixIT\brixmondBundle\Entity\Client;
use BrixIT\brixmondBundle\Entity\Message;
use BrixIT\brixmondBundle\Model\UserNotification;
use Symfony\Component\DependencyInjection\ContainerAware;

class MessageManager extends ContainerAware
{
    public function addMessage(Client $client, Message $message)
    {
        $existing = $this->container->get('doctrine')->getRepository('BrixITbrixmondBundle:Message')->findOneBy([
            'client' => $client,
            'watch' => $message->getWatch(),
            'fixed' => false
        ]);
        if (!$existing) {
            $manager = $this->container->get('doctrine')->getManager();
            $manager->persist($message);
            $manager->flush();

            $users = $this->container->get('fos_user.user_manager')->findUsers();
            $notifyUsers = [];
            foreach ($users as $user) {
                if (in_array('ROLE_ADMIN', $user->getRoles())) {
                    $notifyUsers[] = $user;
                }
            }
            // TODO: Get users linked to the client.

            $notification = new UserNotification();
            $notification->setTitle($message->getTitle());
            $notification->setMessage($message->getMessage());
            $url = $this->container->get('router')->generate('server_charts', ['fqdn' => $client->getFqdn()], true);
            $notification->setUrl($url);
            $notification->setAction('Open in brixmond');

            foreach ($notifyUsers as $user) {
                $this->container->get('user_notifier')->notifyUser($user, $notification);
            }

        }
    }
}