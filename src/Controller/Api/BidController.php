<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\BidCreateDTO;
use App\Entity\Bid;
use App\Messages\WebSocketNotification;
use App\Repository\BidRepository;
use App\Repository\LoadRepository;
use Fresh\CentrifugoBundle\Service\CentrifugoInterface;
use PhpAmqpLib\Exception\AMQPIOException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class BidController extends ApiController
{
    #[Route('/sendBid/{id}', name: 'cargo.showBidForm', methods:['post'])]
    public function createBid(
        int                               $id,
        #[MapRequestPayload] BidCreateDTO $payload,
        BidRepository                     $bidRepository,
        LoadRepository                    $loadRepository,
        CentrifugoInterface               $centrifugo
    ): JsonResponse
    {
        $load = $loadRepository->find($id);

        $bid = new Bid();
        $bid->setLoad($load);
        $bid->setBid($payload->bid);

        try {
            $bidRepository->save($bid);

            $message = sprintf("На вашу заявку поставили ставку в размере %d %s", $payload->bid, 'руб');
            $loadUser = $load->getUser();

            $notification = new WebSocketNotification($message, $loadUser->getId());
            try {
                $centrifugo->publish($notification->toArray(), 'notification');
            } catch (\Throwable $e) {

            }

            return $this->apiJson(['data' => $bid->getId()], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
