<?php

namespace App\Controller;

use App\Entity\ReferralNetwork;
use App\Form\ReferralNetworkType;
use App\Repository\ReferralNetworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/referral/network')]
class ReferralNetworkController extends AbstractController
{
    #[Route('/', name: 'app_referral_network_index', methods: ['GET'])]
    public function index(ReferralNetworkRepository $referralNetworkRepository): Response
    {
        return $this->render('referral_network/index.html.twig', [
            'referral_networks' => $referralNetworkRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_referral_network_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReferralNetworkRepository $referralNetworkRepository): Response
    {
        $referralNetwork = new ReferralNetwork();
        $form = $this->createForm(ReferralNetworkType::class, $referralNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $referralNetworkRepository->add($referralNetwork);
            return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('referral_network/new.html.twig', [
            'referral_network' => $referralNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_referral_network_show', methods: ['GET'])]
    public function show(ReferralNetwork $referralNetwork): Response
    {
        return $this->render('referral_network/show.html.twig', [
            'referral_network' => $referralNetwork,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_referral_network_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReferralNetwork $referralNetwork, ReferralNetworkRepository $referralNetworkRepository): Response
    {
        $form = $this->createForm(ReferralNetworkType::class, $referralNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $referralNetworkRepository->add($referralNetwork);
            return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('referral_network/edit.html.twig', [
            'referral_network' => $referralNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_referral_network_delete', methods: ['POST'])]
    public function delete(Request $request, ReferralNetwork $referralNetwork, ReferralNetworkRepository $referralNetworkRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$referralNetwork->getId(), $request->request->get('_token'))) {
            $referralNetworkRepository->remove($referralNetwork);
        }

        return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
    }
}
