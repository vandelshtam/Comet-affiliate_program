<?php

namespace App\Controller;

use App\Entity\ListReferralNetworks;
use App\Form\ListReferralNetworksType;
use App\Repository\ListReferralNetworksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/list/referral/networks')]
class ListReferralNetworksController extends AbstractController
{
    #[Route('/', name: 'app_list_referral_networks_index', methods: ['GET'])]
    public function index(ListReferralNetworksRepository $listReferralNetworksRepository): Response
    {
        return $this->render('list_referral_networks/index.html.twig', [
            'list_referral_networks' => $listReferralNetworksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_list_referral_networks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ListReferralNetworksRepository $listReferralNetworksRepository): Response
    {
        $listReferralNetwork = new ListReferralNetworks();
        $form = $this->createForm(ListReferralNetworksType::class, $listReferralNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listReferralNetworksRepository->add($listReferralNetwork);
            return $this->redirectToRoute('app_list_referral_networks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('list_referral_networks/new.html.twig', [
            'list_referral_network' => $listReferralNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_list_referral_networks_show', methods: ['GET'])]
    public function show(ListReferralNetworks $listReferralNetwork): Response
    {
        return $this->render('list_referral_networks/show.html.twig', [
            'list_referral_network' => $listReferralNetwork,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_list_referral_networks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ListReferralNetworks $listReferralNetwork, ListReferralNetworksRepository $listReferralNetworksRepository): Response
    {
        $form = $this->createForm(ListReferralNetworksType::class, $listReferralNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listReferralNetworksRepository->add($listReferralNetwork);
            return $this->redirectToRoute('app_list_referral_networks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('list_referral_networks/edit.html.twig', [
            'list_referral_network' => $listReferralNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_list_referral_networks_delete', methods: ['POST'])]
    public function delete(Request $request, ListReferralNetworks $listReferralNetwork, ListReferralNetworksRepository $listReferralNetworksRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$listReferralNetwork->getId(), $request->request->get('_token'))) {
            $listReferralNetworksRepository->remove($listReferralNetwork);
        }

        return $this->redirectToRoute('app_list_referral_networks_index', [], Response::HTTP_SEE_OTHER);
    }
}
