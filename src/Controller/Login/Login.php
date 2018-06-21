<?php
namespace App\Controller\Login;

use App\AuthValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class Login extends AbstractController
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
     *      "login/",
     *      name="login"
     * )
     */
    public function execute()
    {
        if ($this->authValidator->getUser()) {
            return new RedirectResponse($this->generateUrl('dashboard'));
        }
        return $this->render('login.html.twig');
    }
}
