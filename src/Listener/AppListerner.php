<?php

namespace App\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webmozart\Assert\InvalidArgumentException;

class AppListerner implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
            KernelEvents::EXCEPTION => 'onException'
        ];
    }

    public function onRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $request = $event->getRequest();
        if ('json' === $request->getContentType()) {
            $content = $request->getContent();
            $data = json_decode($content, true);
            $request->request->replace($data);
        }
    }

    public function onException(ExceptionEvent $event)
    {
        $response = new JsonResponse();

        $exception = $event->getThrowable();

        switch ($exception) {
            case $exception instanceof NotFoundHttpException:
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                $response->setData('Resource not found');
                break;
            case $exception instanceof AccessDeniedException:
                $response->setStatusCode(Response::HTTP_FORBIDDEN);
                break;
            case $exception instanceof InvalidArgumentException:
                $response->setStatusCode($exception->getCode());
                $response->setData($exception->getMessage());
                break;
            default:
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                $response->setData('Server error');
            break;
        }

        $event->setResponse($response);
    }
}