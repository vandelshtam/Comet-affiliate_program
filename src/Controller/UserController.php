<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ChangeRoleType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user,ManagerRegistry $doctrine,int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user_id = $this->getUser() -> getId();
    
        if($user_id != $id)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        
        $user_roles = $user -> getRoles();
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'user_roles' => $user_roles,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository,EntityManagerInterface $entityManager, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user_id = $this->getUser() -> getId();
    
        if($user_id != $id)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($request);
            if ($request->get('new_email') != $request->get('confirm_email')){
                $this->addFlash(
                    'danger',
                    'Новая почта и ее подтверждение не совпадают, попробуйте ввести еще раз');
                return $this->redirectToRoute('app_user_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $email = $request->get('new_email');
            $user->setEmail($email);
            $user->setUsername($request->get('user')->getUsername());
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно изменили регистрационные данные пользователя'); 
            //$userRepository->add($user);
            //$userRepository->add($user);
            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user_id = $this->getUser() -> getId();
    
        if($user_id != $id)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/role/edit', name: 'app_user_role_edit', methods: ['GET', 'POST'])]
    public function role(Request $request, User $user, EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
    
        $weaving_role = $userRepository->find($id)->getRoles();
        
        if (in_array("ROLE_ADMIN", $weaving_role)) {
            $role_admin = "Отозвать роль админ";
        }
        else
        {
            $role_admin = "Предоставить роль админ";
        }
        if (in_array("ROLE_SUPER_ADMIN", $weaving_role)) {
            $role_super_admin = "Отозвать роль супер админ";
        }
        else
        {
            $role_super_admin = "Предоставить роль супер админ";
        }
        $form = $this->createForm(ChangeRoleType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $value = $request->get('roles');
            if($value == "Предоставить роль супер админ"){
                $roles = ['ROLE_SUPER_ADMIN'];
            }
            if($value == "Предоставить роль админ"  OR $value == "Отозвать роль супер админ"){
                $roles = ['ROLE_ADMIN'];
            }
            if($value == "Отозвать роль админ"){
                $roles = [];
            }
            $user->setRoles($roles);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно изменили роль пользователя'); 
            $userRepository->add($user);
            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/role.html.twig', [
            'user' => $user,
            'form' => $form,
            'roles' => $weaving_role,
            'role_super_admin' => $role_super_admin,
            'role_admin' => $role_admin,
        ]);
    }

    #[Route('/{id}/chenge/email', name: 'app_user_change_email', methods: ['GET', 'POST'])]
    public function changeEmail(Request $request, User $user, UserRepository $userRepository,EntityManagerInterface $entityManager, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user_id = $this->getUser() -> getId();
    
        if($user_id != $id)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->get('new_email') != $request->get('confirm_email')){
                $this->addFlash(
                    'danger',
                    'Новая почта и ее подтверждение не совпадают, попробуйте ввести еще раз');
                return $this->redirectToRoute('app_user_change_email', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $email = $request->get('new_email');
            $user->setEmail($email);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно изменили электронную почту'); 
            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/changeEmail.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/change/username', name: 'app_user_change_username', methods: ['GET', 'POST'])]
    public function changeUsername(Request $request, User $user, UserRepository $userRepository,EntityManagerInterface $entityManager, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user_id = $this->getUser() -> getId();
    
        if($user_id != $id)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($request);
            
            $user->setUsername($request->get('user')->getUsername());
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно изменили имя пользователя'); 
            //$userRepository->add($user);
            //$userRepository->add($user);
            return $this->redirectToRoute('app_user_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/changeUsername.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
