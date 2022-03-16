<?php

namespace App\Controller;

use App\Entity\User;
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

    #[Route('/{user_id}/new', name: 'app_personal_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PersonalDataRepository $personalDataRepository,ManagerRegistry $doctrine, int $user_id): Response
    {
        //dd($user_id);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $personal_data_id = $this->getUser()->getPersonalDataId();
        //dd($personal_data_id);
        if($personal_data_id != NULL)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $personalDatum = new PersonalData();
        $form = $this->createForm(PersonalDataType::class, $personalDatum);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $personalDataRepository->add($personalDatum);
            $personalData = $entityManager->getRepository(PersonalData::class)->findOneBy(['user_id' => $user -> getId()]);
            $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user -> getId()]);
            $user_table->setPersonalDataId($personalData -> getId());
            $random_code = mt_rand();
            $client_code = $user -> getId().$random_code;
            $user_table->setClientCode($client_code);
            $entityManager->persist($user_table);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно зарегистрировали персональные данные');
            // $this->addFlash(
            //     'info',
            //     'Чтобы совершать действия в системе Вам необходимо зарегистрировать персональные данные!');  
            return $this->redirectToRoute('app_personal_data_show', ['personal_user_id' => $user -> getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_data/new.html.twig', [
            'personal_datum' => $personalDatum,
            'form' => $form,
            'user_id' => $user_id,
            'new_user_make' => true,
        ]);
    }

    
    #[Route('/{personal_user_id}/show', name: 'app_personal_data_show', methods: ['GET', 'POST'])]
    public function show(ManagerRegistry $doctrine,int $personal_user_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $repository = $doctrine->getRepository(PersonalData::class);
        //$personal_data_id = $this->getUser()->getPersonalDataId();
        //dd($personal_user_id);
        if($personal_user_id != NULL)
        {
            if($repository->findOneBy(['user_id' => $user -> getId()])->getUserId() == false)
            {
                $this->denyAccessUnlessGranted('ROLE_ADMIN');
            }
        }
            
        $repository = $doctrine->getRepository(PersonalData::class);
        $personalDatum = $doctrine->getRepository(PersonalData::class)->findOneBySomeField($user -> getId());
        //dd($personalDatum);
        return $this->render('personal_data/show.html.twig', [
            'personal_datum' => $personalDatum,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personal_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PersonalData $personalDatum, PersonalDataRepository $personalDataRepository,int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()->getPersonalDataId() != $id)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $user = $this->getUser();
        $form = $this->createForm(PersonalDataType::class, $personalDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalDataRepository->add($personalDatum);
            return $this->redirectToRoute('app_personal_data_show', ['personal_user_id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_data/edit.html.twig', [
            'personal_datum' => $personalDatum,
            'form' => $form,
            'new_user_make' => false,
            'user_id' => $user->getId(),
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
