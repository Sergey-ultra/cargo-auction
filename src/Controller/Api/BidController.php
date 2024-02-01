<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\BidCreateDTO;
use App\Entity\Bid;
use App\Repository\BidRepository;
use App\Repository\LoadRepository;
use App\WebSocket\Manager\MessageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class BidController extends AbstractController
{
    #[Route('/sendBid/{id}', name: 'cargo.showBidForm', methods:['post'])]
    public function showBidForm(
        int $id,
        #[MapRequestPayload] BidCreateDTO $payload,
        BidRepository $bidRepository,
        LoadRepository $loadRepository,
        MessageManager $messageManager,
    ): JsonResponse
    {
        $load = $loadRepository->find($id);

        $bid = new Bid();
        $bid->setLoad($load);
        $bid->setBid($payload->bid);

        try {
            $bidRepository->save($bid);

            $message = 'На вашу заявку поставили ставку';
            $loadUser = $load->getUser();
            $messageManager->createNotificationMessage($message, (string)$loadUser->getId());

            return $this->json(['data' => $bid], Response::HTTP_CREATED, [], [
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    }]
            );
        } catch (\Throwable $e) {
            return $this->json(
                [
                    'error' => $e,
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
