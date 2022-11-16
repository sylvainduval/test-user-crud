<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UsersController extends AbstractController
{
	private UserRepository $userRepository;

	public function __construct(ManagerRegistry $doctrine)
	{
		$this->userRepository = $doctrine->getRepository(User::class);
	}

	#[Route('/', name: 'users', methods: ['GET'])]
	public function listUsers(): Response
	{
		$users = $this->userRepository->findAll();

		return $this->render('user/list.html.twig', [
			'users' => $users,
		]);
	}

	#[Route('/add', name: 'add_user', methods: ['GET', 'POST'])]
	public function addUser(Request $request, SerializerInterface $serializer): Response
	{
		$form = $this->createForm(UserType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			$data['birthdayDate'] = $data['birthdayDate']->format('Y-m-d');
			$user = $serializer->denormalize($data, User::class);
			$user->setActive(true);
			try {
				$this->userRepository->save($user, true);
			} catch (BadRequestException $exception) {
				throw new BadRequestHttpException($exception->getMessage());
			}

			return new RedirectResponse('./');
		}

		return $this->render('user/edit.html.twig', [
			'form' => $form->createView(),
			'title' => 'Ajouter un utilisateur',
		]);
	}

	#[Route('/user/{id}', name: 'edit_user', methods: ['GET', 'POST'])]
	public function editUser(Request $request, string $id): Response
	{
		$user = $this->userRepository->find($id);
		if ($user === null) {
			throw new BadRequestHttpException();
		}
		$form = $this->createForm(UserType::class, $user);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			try {
				$user->setActive(true);
				$this->userRepository->save($user, true);
			} catch (BadRequestException $exception) {
				throw new BadRequestHttpException($exception->getMessage());
			}

			return $this->redirectToRoute('users');
		}

		return $this->render('user/edit.html.twig', [
			'form' => $form->createView(),
			'title' => 'Modifier l\'utilisateur',
		]);
	}

	#[Route('/user/{id}/disable', name: 'disable_user', methods: ['POST'])]
	public function disableUser(string $id): JsonResponse
	{
		$user = $this->userRepository->find($id);
		if ($user === null || $user->isActive() === false) {
			throw new BadRequestHttpException();
		}

		$user->setActive(false);

		$this->userRepository->save($user, true);

		return new JsonResponse(['success' => true]);
	}
}