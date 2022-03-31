<?php

namespace App\Controller;

use App\Entity\SettingOptions;
use App\Form\SettingOptionsType;
use App\Repository\SettingOptionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/setting/options')]
class SettingOptionsController extends AbstractController
{
    #[Route('/', name: 'app_setting_options_index', methods: ['GET'])]
    public function index(SettingOptionsRepository $settingOptionsRepository): Response
    {
        return $this->render('setting_options/index.html.twig', [
            'setting_options' => $settingOptionsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_setting_options_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SettingOptionsRepository $settingOptionsRepository): Response
    {
        $settingOption = new SettingOptions();
        $form = $this->createForm(SettingOptionsType::class, $settingOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settingOptionsRepository->add($settingOption);
            return $this->redirectToRoute('app_setting_options_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('setting_options/new.html.twig', [
            'setting_option' => $settingOption,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_setting_options_show', methods: ['GET'])]
    public function show(SettingOptions $settingOption): Response
    {
        return $this->render('setting_options/show.html.twig', [
            'setting_option' => $settingOption,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_setting_options_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SettingOptions $settingOption, SettingOptionsRepository $settingOptionsRepository): Response
    {
        $form = $this->createForm(SettingOptionsType::class, $settingOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settingOptionsRepository->add($settingOption);
            return $this->redirectToRoute('app_setting_options_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('setting_options/edit.html.twig', [
            'setting_option' => $settingOption,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_setting_options_delete', methods: ['POST'])]
    public function delete(Request $request, SettingOptions $settingOption, SettingOptionsRepository $settingOptionsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$settingOption->getId(), $request->request->get('_token'))) {
            $settingOptionsRepository->remove($settingOption);
        }

        return $this->redirectToRoute('app_setting_options_index', [], Response::HTTP_SEE_OTHER);
    }
}
