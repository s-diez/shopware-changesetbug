<?php declare(strict_types=1);

namespace ChangeSetBug\Core\Controller;


use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"api"})
 */
class ChangeSetBug extends AbstractController
{
    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;

    /**
     * @param EntityRepositoryInterface $productRepository
     */
    public function __construct(EntityRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/api/_action/changesetbug/test", name="api.action.changesetbug.test", defaults={"auth_required"=false}, methods={"GET"})
     * @param Context $context
     * @return JsonApiResponse
     */
    public function test(Context $context): JsonApiResponse
    {
        $ids = $this->productRepository->search((new Criteria())->setLimit(4), $context)->getIds();
        $data = [
            ['id' => array_pop($ids), 'weight' => 11],
            ['id' => array_pop($ids), 'weight' => 12],
            ['id' => array_pop($ids), 'weight' => 13],
            ['id' => array_pop($ids), 'weight' => 14],
        ];
        $this->productRepository->update($data, $context);
        return new JsonApiResponse();
    }
}
