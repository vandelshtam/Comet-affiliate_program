<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pakege;
use App\Entity\PersonalData;
use App\Entity\TablePakage;
use App\Entity\TokenRate;
use App\Form\PakegeType;
use App\Repository\PakegeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/pakege')]
class PakegeController extends AbstractController
{
    #[Route('/', name: 'app_pakege_index', methods: ['GET'])]
    public function index(PakegeRepository $pakegeRepository, ManagerRegistry $doctrine): Response
    {
        //$pakege = $doctrine->getRepository(Pakege::class)->find(8);
        //$user = $doctrine->getRepository(User::class)->find(2);
        //$userUsername = $pakege->getUser();
        //$user_pakeges = $user -> getPakeges();
        // $collection = new ArrayCollection();
        // $collection = setElements($user_pakeges);
        //dd($user);
        return $this->render('pakege/index.html.twig', [
            'pakeges' => $pakegeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pakege_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PakegeRepository $pakegeRepository, ManagerRegistry $doctrine): Response
    {
        $pakege = new Pakege();
        $form = $this->createForm(PakegeType::class, $pakege);
        $form->handleRequest($request);
        
        // $user = $this -> getUser();
        // $user_id = $user -> getId();
        // $entityManager = $doctrine->getManager();
        // $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);
        // $personal_data_table = $entityManager->getRepository(PersonalData::class)->findOneBy(['user_id' => $user -> getId()]);
        $unique_code1 = $this->random_string(10);
        $unique_code2 = $this->random_string(10);
        $unique_code = $unique_code1.$unique_code2;
        $collection = new ArrayCollection();
        $collection -> add($unique_code);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($request);
            $pakegeRepository->add($pakege);
            $unique = $form->get('unique_code')->getData();
        
            //$user->setUsername($request->get('user')->getUsername());
            return $this->redirectToRoute('app_pakege_new_choice', ['unique' => $unique], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pakege/new.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
            'unique_code' => $collection[0],
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
    public function edit(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository, ManagerRegistry $doctrine, int $id): Response
    {
        $form = $this->createForm(PakegeType::class, $pakege);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();
        $pakage_comet = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        $unique = $pakage_comet -> getUniqueCode();

        if ($form->isSubmitted() && $form->isValid()) {
            $pakegeRepository->add($pakege);
            return $this->redirectToRoute('app_pakege_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pakege/edit.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
            'unique_code' => $unique,
        ]);
    }

    #[Route('/{id}', name: 'app_pakege_delete', methods: ['POST'])]
    public function delete(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pakege->getId(), $request->request->get('_token'))) {
            $pakegeRepository->remove($pakege);
            $this->addFlash(
                'danger',
                'Вы успешно удалили пакет, на электронную почту отправлено подтверждение операции');
        }

        return $this->redirectToRoute('app_pakege_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new/{unique}/choice', name: 'app_pakege_new_choice', methods: ['GET', 'POST'])]
    public function newChoice (Request $request, PakegeRepository $pakegeRepository, ManagerRegistry $doctrine, string $unique): Response
    {
        //dd($unique);
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $entityManager = $doctrine->getManager();
        $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);
        $personal_data_table = $entityManager->getRepository(PersonalData::class)->findOneBy(['user_id' => $user -> getId()]);
        $client_code = $personal_data_table -> getClientCode();
        $pakage_comet = $entityManager->getRepository(Pakege::class)->findOneBy(['unique_code' => $unique]);
        $pakage_comet_name = $pakage_comet -> getName();
            //dd($unique_code);
            //$client_code = $personal_data_table -> getClientCode();
            
            //$client_code = $personal_data_table -> getClientCode();
            $pakage_table = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $pakage_comet_name]);
            $token_table =  $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();
            $price_usdt = $pakage_table -> getPricePakage();
            $price_token = $price_usdt * $token_table;
            
            //dd($pakage_comet);
             $pakage_comet -> setUserId($user_id);
             $pakage_comet -> setPrice($price_usdt);
             $pakage_comet -> setToken($price_token);
             $pakage_comet -> setClientCode($client_code);
            
            //dd($pakage_comet);
            //$pakegeRepository->add($price_usdt);
            //$pakegeRepository->add($name);
            
            //$user->setUsername($request->get('user')->getUsername());
            //$entityManager->persist($user_table);
            
            //$entityManager->persist($pakege_comet);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно приобрели новый пакет, на электронную почту отправлено подтверждение операции');
            $this->addFlash(
                'info',
                'Чтобы пакет начал работать вы должны активировать пакет!');     
            return $this->redirectToRoute('app_pakege_index', [], Response::HTTP_SEE_OTHER);
    }
    public function random_string ($str_length)
{
    $str_characters = array (0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

	// Функция может генерировать случайную строку и с использованием кириллицы
    //$str_characters = array (0,1,2,3,4,5,6,7,8,9,'а','б','в','г','д','е','ж','з','и','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','э','ю','я');

    // Возвращаем ложь, если первый параметр равен нулю или не является целым числом
    if (!is_int($str_length) || $str_length < 0)
    {
        return false;
    }

    // Подсчитываем реальное количество символов, участвующих в формировании случайной строки и вычитаем 1
    $characters_length = count($str_characters) - 1;

    // Объявляем переменную для хранения итогового результата
    $string = '';

    // Формируем случайную строку в цикле
    for ($i = $str_length; $i > 0; $i--)
    {
        $string .= $str_characters[mt_rand(0, $characters_length)];
    }

    // Возвращаем результат
    return $string;
}
}
