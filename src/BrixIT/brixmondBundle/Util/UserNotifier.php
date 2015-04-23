<?php

namespace BrixIT\brixmondBundle\Util;


use BrixIT\brixmondBundle\Entity\User;
use BrixIT\brixmondBundle\Model\UserNotification;
use Sly\PushOver\Model\Push;
use Sly\PushOver\PushManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class UserNotifier extends ContainerAware
{
    public function notify($username, UserNotification $notification)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername($username);
        $this->notifyUser($user, $notification);
    }

    public function notifyUser(User $user, UserNotification $notification)
    {
        if ($user->getPushoverKey() == null || $user->getPushoverKey() == '') {
            $this->notifyByEmail($user, $notification);
        } else {
            $this->notifyByPushover($user, $notification);
        }
    }

    private function notifyByEmail(User $user, UserNotification $notification)
    {
        $body = $notification->getMessage();
        $body .= "\r\n\r\n" . $notification->getAction() . ':' . "\r\n";
        $body .= $notification->getUrl();

        $mailer = $this->container->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject($notification->getTitle())
            ->setFrom($this->container->getParameter('mailer_from'))
            ->setTo($user->getEmail())
            ->setBody($body, 'text/html');
        $mailer->send($message);
    }

    private function notifyByPushover(User $user, UserNotification $notification)
    {
        $pushOverKey = $user->getPushoverKey();
        $message = new Push();
        $message->setTitle($notification->getTitle());
        $message->setMessage($notification->getMessage());
        $message->setUrl($notification->getUrl());
        $message->setUrlTitle($notification->getAction());

        $pushManager = new PushManager($pushOverKey, $this->container->getParameter('pushover_token'));
        $pushManager->push($message);
    }
}