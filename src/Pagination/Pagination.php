<?php

namespace App\Pagination;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Pagination
{
    const LIMIT = 10;
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

    public function create(array $data, $route): array
    {
        $page = $this->requestStack->getCurrentRequest()->query->getInt('page', 1);
        $paginator = $this->paginator->paginate(
            $data,
            $page,
            self::LIMIT
        );
        $pages = (int) ceil($paginator->getTotalItemCount() / self::LIMIT);
        $this->add('page', $page);
        $this->add('limit', self::LIMIT);
        $this->add('pages', $pages);
        if ($page !== 1) {
           $this->addLink('first', $this->generator->generate($route, ['page' => 1]));
        }
        if ($page < $pages) {
            $this->addLink('self', $this->generator->generate($route, ['page' => $page]));
            $this->addLink('next', $this->generator->generate($route, ['page' => $page + 1]));
        }
        if ($page <= $pages && $page > 1) {
            $this->addLink('previous', $this->generator->generate($route, ['page' => $page - 1]));
        }
        $this->addLink('last', $this->generator->generate($route, ['page' => $pages]));
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