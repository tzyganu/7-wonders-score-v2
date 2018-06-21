<?php
namespace App\Controller\Login;

use App\AuthValidator;
use App\Entity\User;
use App\Hash;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class Post extends AbstractController
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var AuthValidator
     */
    private $authValidator;
    /**
     * @var Hash
     */
    private $hash;

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * Post constructor.
     * @param RequestStack $requestStack
     * @param AuthValidator $authValidator
     * @param Hash $hash
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        RequestStack $requestStack,
        AuthValidator $authValidator,
        Hash $hash,
        ManagerRegistry $managerRegistry
    ) {
        $this->requestStack     = $requestStack;
        $this->authValidator    = $authValidator;
        $this->hash             = $hash;
        $this->managerRegistry  = $managerRegistry;
    }

    /**
     * @return string
     * @Route(
     *      "login/post/",
     *      name="login/post"
     * )
     */
    public function execute()
    {
        if ($this->authValidator->getUser()) {
            return new RedirectResponse($this->generateUrl('dashboard'));
        }
        try {
            $username = $this->requestStack->getCurrentRequest()->get('username');
            $password = $this->requestStack->getCurrentRequest()->get('password');
            if (!$username || !$password) {
                throw new \Exception('Username and password should not me empty');
            }
            /** @var User $user */
            $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['username' => $username, 'active' => 1]);
            if (!$user) {
                throw new \Exception("User not found");
            }
            if ($user->getPassword() == $this->hash->hash($password)) {
                $this->addFlash('success', 'Welcome '. $user->getUsername());
                $this->authValidator->setUser($user);
                return new RedirectResponse($this->generateUrl('dashboard'));
            }
            throw new \Exception("Wrong password or not active user");
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return new RedirectResponse($this->generateUrl('dashboard'));
        }
    }
}
