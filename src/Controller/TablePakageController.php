<?php

namespace App\Controller;

use App\Entity\TablePakage;
use App\Form\TablePakageType;
use App\Repository\TablePakageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/table/pakage')]
class TablePakageController extends AbstractController
{
    #[Route('/', name: 'app_table_pakage_index', methods: ['GET'])]
    public function index(TablePakageRepository $tablePakageRepository): Response
    {
        return $this->render('table_pakage/index.html.twig', [
            'table_pakages' => $tablePakageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_table_pakage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TablePakageRepository $tablePakageRepository): Response
    {
        $tablePakage = new TablePakage();
        $form = $this->createForm(TablePakageType::class, $tablePakage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tablePakageRepository->add($tablePakage);
            return $this->redirectToRoute('app_table_pakage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('table_pakage/new.html.twig', [
            'table_pakage' => $tablePakage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_table_pakage_show', methods: ['GET'])]
    public function show(TablePakage $tablePakage): Response
    {
        return $this->render('table_pakage/show.html.twig', [
            'table_pakage' => $tablePakage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_table_pakage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TablePakage $tablePakage, TablePakageRepository $tablePakageRepository): Response
    {
        $form = $this->createForm(TablePakageType::class, $tablePakage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tablePakageRepository->add($tablePakage);
            return $this->redirectToRoute('app_table_pakage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('table_pakage/edit.html.twig', [
            'table_pakage' => $tablePakage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_table_pakage_delete', methods: ['POST'])]
    public function delete(Request $request, TablePakage $tablePakage, TablePakageRepository $tablePakageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tablePakage->getId(), $request->request->get('_token'))) {
            $tablePakageRepository->remove($tablePakage);
        }

        return $this->redirectToRoute('app_table_pakage_index', [], Response::HTTP_SEE_OTHER);
    }
}
