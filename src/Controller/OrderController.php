<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\OrdersFilter;
use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/', name: 'cargo.index', methods:['get'])]
    public function index(#[MapQueryString] ?OrdersFilter $filter, Request $request, OrderRepository $repository): Response
    {
        $page = $request->query->getInt('page', 1);

        $paginator = $repository->getPaginator($page, $filter);
        $totalCount = $paginator->count();

        return $this->render('index.html.twig', [
            'list' => $paginator,
            'page' => $page,
            'totalCount' => $totalCount,
            'lastPage' => ceil($totalCount / OrderRepository::PAGINATOR_PER_PAGE)
        ]);
    }

    #[Route('/create', name: 'cargo.create', methods:['get'])]
    public function edit(): Response
    {
        return $this->render('form.html.twig', [
            'cargoTypes' => Order::CARGO_TYPES,
            'packageTypes' => Order::PACKAGE_TYPES,
        ]);
    }


    #[Route('/create', name: 'cargo.store', methods:['post'] )]
    public function store(Request $request, OrderRepository $repository): RedirectResponse|Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid() ) {
            $order->setUser($this->getUser());

            $repository->save($order);
            return $this->redirectToRoute('cargo.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('form.html.twig', [
            'cargoTypes' => Order::CARGO_TYPES,
            'packageTypes' => Order::PACKAGE_TYPES,
        ]);
    }
}
