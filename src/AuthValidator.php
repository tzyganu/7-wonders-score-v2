<?php
namespace App;

use App\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthValidator
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * Login constructor.
     * @param SessionInterface $session
     */
    public function __construct(
        SessionInterface $session,
        ManagerRegistry $managerRegistry
    ) {
        $this->session = $session;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        $user = $this->session->get('user');
        if (!$user) {
            return null;
        }
        if (!($user instanceof User)) {
            return null;
        }
        /** @var User $user */
        $userId = $user->getId();
        if (!$userId) {
            return null;
        }
        /** @var User $dbUser */
        $dbUser = $this->managerRegistry->getRepository(User::class)->find($userId);
        if (!$dbUser) {
            $this->session->set('user', null);
            return null;
        }
        if (!$dbUser->getActive()) {
            $this->session->set('user', null);
            return null;
        }
        $this->session->set('user', $dbUser);
        return $this->session->get('user');
    }

    /**
     * @param User $user
     */
    public function setUser(User $user = null)
    {
        $this->session->set('user', $user);
    }

}
