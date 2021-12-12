<?php

namespace App\Pagination;

use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Pagination
{
    /**
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    private $items;
    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $generator;

    public function __construct(
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        UrlGeneratorInterface $generator
    )
    {
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
        $this->generator = $generator;
    }

    public function create(Query $query): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $route = $request->attributes->get('_route');
        $params = $request->attributes->get('_route_params', []);
        $paginator = $this->paginator->paginate(
            $query,
            $page,
            $limit
        );
        $pages = (int) ceil($paginator->getTotalItemCount() / $limit);
        $this->add('page', $page);
        $this->add('limit', $limit);
        $this->add('pages', $pages);
        if ($page !== 1) {
           $this->addLink('first', $this->generator->generate($route, ['page' => 1] + $params));
        }
        if ($page < $pages) {
            $this->addLink('self', $this->generator->generate($route, ['page' => $page] + $params));
            $this->addLink('next', $this->generator->generate($route, ['page' => $page + 1] + $params));
        }
        if ($page <= $pages && $page > 1) {
            $this->addLink('previous', $this->generator->generate($route, ['page' => $page - 1] + $params));
        }
        $this->addLink('last', $this->generator->generate($route, ['page' => $pages] + $params));
        $this->add('items', $paginator->getItems());
        return $this->items;
    }

    public function add($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function addLink($key, $value)
    {
        $this->items['_links'][$key] = $value;
    }

}