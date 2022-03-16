<?php

namespace App\Controller;

use App\Entity\Pakege;
use App\Form\PakegeType;
use App\Repository\PakegeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pakege')]
class PakegeController extends AbstractController
{
    #[Route('/', name: 'app_pakege_index', methods: ['GET'])]
    public function index(PakegeRepository $pakegeRepository): Response
    {
        return $this->render('pakege/index.html.twig', [
            'pakeges' => $pakegeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pakege_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PakegeRepository $pakegeRepository): Response
    {
        $pakege = new Pakege();
        $form = $this->createForm(PakegeType::class, $pakege);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pakegeRepository->add($pakege);
            return $this->redirectToRoute('app_pakege_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pakege/new.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pakege_show', methods: ['GET'])]
    public function show(Pakege $pakege): Response
    {
        return $this->render('pakege/show.html.twig', [
            'pakege' => $pakege,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pakege_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository): Response
    {
        $form = $this->createForm(PakegeType::class, $pakege);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pakegeRepository->add($pakege);
            return $this->redirectToRoute('app_pakege_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pakege/edit.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pakege_delete', methods: ['POST'])]
    public function delete(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pakege->getId(), $request->request->get('_token'))) {
            $pakegeRepository->remove($pakege);
        }

        return $this->redirectToRoute('app_pakege_index', [], Response::HTTP_SEE_OTHER);
    }
}
