<?php

namespace App\Controller;

use App\Entity\PersonalData;
use App\Form\PersonalDataType;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\PersonalDataRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/personal/data')]
class PersonalDataController extends AbstractController
{
    #[Route('/', name: 'app_personal_data_index', methods: ['GET'])]
    public function index(PersonalDataRepository $personalDataRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('personal_data/index.html.twig', [
            'personal_datas' => $personalDataRepository->findAll(),
            'title' => 'Personal data',
        ]);
    }

    #[Route('/new', name: 'app_personal_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PersonalDataRepository $personalDataRepository,ManagerRegistry $doctrine): Response
    {
        $personalDatum = new PersonalData();
        $form = $this->createForm(PersonalDataType::class, $personalDatum);
        $form->handleRequest($request);
        $user = $this->getUser();
        //dd($user);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalDataRepository->add($personalDatum);
            $entityManager = $doctrine->getManager();
            //$personalData = $entityManager->getRepository(PersonalData::class)->find($id);
           // $product->setName('New product name!');
            
            return $this->redirectToRoute('app_personal_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_data/new.html.twig', [
            'personal_datum' => $personalDatum,
            'form' => $form,
            'user_id' => $user->getId(),
        ]);
    }

    
    #[Route('/{personal_user_id}/show', name: 'app_personal_data_show', methods: ['GET', 'POST'])]
    public function show(ManagerRegistry $doctrine,int $personal_user_id): Response
    {
        //dd($id);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $repository = $doctrine->getRepository(PersonalData::class);
        //$user_id = $repository->findOneBy(['user_id' => $user -> getId()])->getUserId();
        //$personal_data_id = $this->getUser()->getPersonalDataId();
        
        
        if($personal_user_id != NULL)
        {
            if($repository->findOneBy(['user_id' => $user -> getId()])->getUserId() == false)
            {
                $this->denyAccessUnlessGranted('ROLE_ADMIN');
            }
        }
            
        //dd($this->getUser()->getId());
        $repository = $doctrine->getRepository(PersonalData::class);
        $personalDatum = $doctrine->getRepository(PersonalData::class)->findOneBySomeField($user -> getId());
        //dd($user_id);
        return $this->render('personal_data/show.html.twig', [
            'personal_datum' => $personalDatum,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personal_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PersonalData $personalDatum, PersonalDataRepository $personalDataRepository,int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()->getId() != $id)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $form = $this->createForm(PersonalDataType::class, $personalDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalDataRepository->add($personalDatum);
            return $this->redirectToRoute('app_personal_data_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_data/edit.html.twig', [
            'personal_datum' => $personalDatum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_personal_data_delete', methods: ['POST'])]
    public function delete(Request $request, PersonalData $personalDatum, PersonalDataRepository $personalDataRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personalDatum->getId(), $request->request->get('_token'))) {
            $personalDataRepository->remove($personalDatum);
        }

        return $this->redirectToRoute('app_personal_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
