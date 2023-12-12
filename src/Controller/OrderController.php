<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\OrdersFilter;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/', name: 'cargo.index', methods:['get'])]
    public function index(#[MapQueryString] ?OrdersFilter $filter, OrderRepository $repository): Response
    {
        $list = [];

        if (null !== $filter) {
            $criteria = $filter->toArray();
            $list = $repository->findBy($criteria);
        }

        return $this->render('index.html.twig', ['list' => $list]);
    }

    #[Route('/edit', name: 'cargo.edit', methods:['get'])]
    public function edit(): Response
    {
        $cargoTypes = [
            'продукты питания',
            'ТНП непродовольственные',
            'оборудование и запчасти',
            'строймариалы',
            'металл',
            'пиломатериалы',
            'пустая тара и упаковка',
            'картон, бумага, макулатура',
            'химия',
            'топливо и смазки',
            'контейнер',
            'транспортные средства',
            'с/х сырье и продукция',
            'личные вещи, переезд',
            'сборный груз',
            'другой',
        ];

        $packageTypes = [
            [
                'value' => 'упаковка',
                'children' => [
                    "bigbag" => 'биг бэги',
                    "pallet" => 'паллеты',
                    "box" => 'коробки',
                    "case" => 'ящики',
                    "barrel" => 'бочки',
                    "bag" => 'мешки/сетки',
                    "pack" => 'пачки',
                ],
            ],
            [
                'value' => "без упаковки",
                'children' => [
                    "in_bulk" => 'навалом/насыпью',
                    "fill" => 'наливной груз',
                    "other" => 'другая'
                ],
            ],
        ];

        return $this->render('form.html.twig', compact('cargoTypes', 'packageTypes'));
    }

    #[Route('/edit', name: 'cargo.store', methods:['post'] )]
    public function store(#[MapQueryString] Order $order, OrderRepository $repository): RedirectResponse
    {
        dd($order);

        $repository->save($order);

        return $this->redirectToRoute('main-page', [], Response::HTTP_SEE_OTHER);
    }
}
