<?php

namespace App\Controller;

use App\AR;
use App\Entity\Product;
use App\Pagination\Pagination;
use App\Repository\ProductRepository;
use App\Service\SerialisationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/phones")
 */
class ProductController extends AbstractController
{

    /**
     * @var \App\Repository\ProductRepository
     */
    private $productRepository;
    /**
     * @var \App\Service\SerialisationService
     */
    private $serialisationService;
    /**
     * @var \App\Pagination\Pagination
     */
    private $pagination;

    public function __construct(
        ProductRepository $productRepository,
        SerialisationService $serialisationService,
        Pagination $pagination
    )
    {
        $this->productRepository = $productRepository;
        $this->serialisationService = $serialisationService;
        $this->pagination = $pagination;
    }

    /**
     * @OA\Tag(name="Phone")
     *
     * @Route(methods={"GET"}, name="app_phone_list")
     */
    public function all(): Response
    {
        $query = $this->productRepository->createQueryBuilder('p')->getQuery();
        $data = $this->pagination->create($query);
        $products = $this->serialisationService->serialize($data, ['list']);
        return AR::ok($products);
    }

    /**
     * @OA\Tag(name="Phone")
     *
     * @Route("/{id}", methods={"GET"}, name="app_phone_details")
     */
    public function details(Product $product): Response
    {
        $product = $this->serialisationService->serialize($product, ['show']);

        return AR::ok($product);
    }

}