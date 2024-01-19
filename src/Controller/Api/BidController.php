<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Bid;
use App\Repository\BidRepository;
use App\Repository\LoadRepository;
use App\WebSocket\Manager\MessageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class BidController extends AbstractController
{
    #[Route('/sendBid/{id}', name: 'cargo.showBidForm', methods:['post'])]
    public function showBidForm(
        int $id,
        Request $request,
        BidRepository $bidRepository,
        LoadRepository $loadRepository,
        MessageManager $messageManager,
    ): JsonResponse
    {
        $request = json_decode($request->getContent());
        $bid = new Bid();

        $load = $loadRepository->find($id);

        $loadUser = $load->getUser();

        $bid->setLoad($load);
        $bid->setBid((int)$request->bid);

        try {
            $bidRepository->save($bid);
            $message = 'На вашу заявку поставили ставку';

            $messageManager->createNotificationMessage($message, (string)$loadUser->getId());

            return $this->json(
                [
                    'data' => $request->bid,
                ],
                Response::HTTP_CREATED
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
