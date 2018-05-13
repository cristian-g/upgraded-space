<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class NotificationsController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(Request $request, Response $response, array $args) {
        $notifications = ($this->container->get('get_notifications_use_case'))($_SESSION["user_id"]);

        foreach ($notifications as $key => $notification) {
            $notifications[$key]["created_at"] = ucfirst(self::computeRelativeTime($notifications[$key]["created_at"]));
        }

        return $this->container->get('view')
            ->render($response, 'notifications.twig', ['notifications' => $notifications]);
    }

    static function computeRelativeTime($timestamp)
    {
        if (!is_int($timestamp))
        {
            $timestamp=strtotime($timestamp, 0);
        }
        $diff = time() - $timestamp;
        if ($diff <= 0) return 'Ahora';
        else if ($diff < 60) return "hace ".self::addS(floor($diff), ' segundo(s)');
        else if ($diff < 60*60) return "hace ".self::addS(floor($diff/60), ' minuto(s)');
        else if ($diff < 60*60*24) return "hace ".self::addS(floor($diff/(60*60)), ' hora(s)');
        else if ($diff < 60*60*24*30) return "hace ".self::addS(floor($diff/(60*60*24)), ' día(s)');
        else if ($diff < 60*60*24*30*12) return "hace ".self::addS(floor($diff/(60*60*24*30)), ' mes(es)');
        else return "hace ".self::addS(floor($diff/(60*60*24*30*12)), ' año(s)');
    }

    static function addS($val, $sentence)
    {
        if ($val > 1) return $val.str_replace(array('(s)','(es)'),array('s','es'), $sentence);
        else return $val.str_replace('(s)', '', $sentence);
    }
}