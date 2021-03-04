<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @Route("/user", name="user")
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
     * @Route("/users", name="users")
     */
    public function index(): Response
    {
        $users = $this->repository->findAll();
        dump($users);

        return new JsonResponse(
            $users
        );
    }

    /**
     * Registers a new User
     * @Route("/create", name="create")
     * @param EntityManagerInterface $entityManager is used to persist the new user in the DB
     * @param UserPasswordEncoderInterface $passwordEncoder is used to encode the password field
     * @return Response
     */
    public function create(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        /**
         * @var $user User
         */
        $user = new User;
        $password = $passwordEncoder->encodePassword($user, "hahahaha");

        $user->setUsername("Un petit patapon")
            ->setPassword($password);
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(
            [
                $user,
            ]
        );
    }

    /**
     * This method attempts to the User in.
     * @Route("/login", name="login", methods={"POST"})
     * @param UserPasswordEncoderInterface $passwordEncoder Used to check if password is valid
     * @param Request $request
     * @return JsonResponse
     */
    public function login(UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $user = $this->repository->findOneBy(['username' => $request->get('username')]);
        if ($user && $passwordEncoder->isPasswordValid($user, $request->get('password'))) {
            return new JsonResponse("C'est OK");
        }

        return new JsonResponse("C'OOOOH.");
    }
}
