<?php

namespace App\Controller;

use App\Entity\TokenRate;
use App\Form\TokenRateType;
use App\Repository\TokenRateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/token/rate')]
class TokenRateController extends AbstractController
{
    #[Route('/', name: 'app_token_rate_index', methods: ['GET'])]
    public function index(TokenRateRepository $tokenRateRepository): Response
    {
        return $this->render('token_rate/index.html.twig', [
            'token_rates' => $tokenRateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_token_rate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TokenRateRepository $tokenRateRepository): Response
    {
        $tokenRate = new TokenRate();
        $form = $this->createForm(TokenRateType::class, $tokenRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tokenRateRepository->add($tokenRate);
            return $this->redirectToRoute('app_token_rate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('token_rate/new.html.twig', [
            'token_rate' => $tokenRate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_token_rate_show', methods: ['GET'])]
    public function show(TokenRate $tokenRate): Response
    {
        return $this->render('token_rate/show.html.twig', [
            'token_rate' => $tokenRate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_token_rate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TokenRate $tokenRate, TokenRateRepository $tokenRateRepository): Response
    {
        $form = $this->createForm(TokenRateType::class, $tokenRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tokenRateRepository->add($tokenRate);
            return $this->redirectToRoute('app_token_rate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('token_rate/edit.html.twig', [
            'token_rate' => $tokenRate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_token_rate_delete', methods: ['POST'])]
    public function delete(Request $request, TokenRate $tokenRate, TokenRateRepository $tokenRateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tokenRate->getId(), $request->request->get('_token'))) {
            $tokenRateRepository->remove($tokenRate);
        }

        return $this->redirectToRoute('app_token_rate_index', [], Response::HTTP_SEE_OTHER);
    }
}
