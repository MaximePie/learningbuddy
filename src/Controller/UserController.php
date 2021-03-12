<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use RequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @Route("/api/user", name="user")
 * @package App\Controller
 */
class UserController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="users")
     * @return Response
     */
    public function index(): Response
    {
        /**
         * @var $users User[]
         */
        $users = $this->repository->findAll();
        $data = [];
        foreach($users as $user) {
            $data[] = $user->formatedForView();
        }

        return new JsonResponse($data);
    }

    /**
     * Registers a new User
     * @Route("/create", name="create")
     * @param EntityManagerInterface $entityManager is used to persist the new user in the DB
     * @param UserPasswordEncoderInterface $passwordEncoder is used to encode the password field
     * @param Request $request
     * @return Response
     */
    public function create(
        EntityManagerInterface $entityManager,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        /**
         * @var $user User
         */
        $user = new User;
        $password = RequestService::getFromRequest($request, 'password');
        $password = $passwordEncoder->encodePassword($user, $password);
        $email = RequestService::getFromRequest($request, 'email');

        $user->setEmail($email)
            ->setPassword($password);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            "code" => 200
        ]);
    }
}
