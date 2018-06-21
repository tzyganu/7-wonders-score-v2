<?php
namespace App\Controller\Login;

use App\AuthValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class Logout extends AbstractController
{
    /**
     * @var AuthValidator
     */
    private $authValidator;

    /**
     * Login constructor.
     * @param AuthValidator $authValidator
     */
    public function __construct(
        AuthValidator $authValidator
    ) {
        $this->authValidator = $authValidator;
    }

    /**
     * @return string
     * @Route(
     *      "logout/",
     *      name="logout"
     * )
     */
    public function execute()
    {
        $this->authValidator->setUser(null);
        return new RedirectResponse($this->generateUrl('dashboard'));
    }
}
