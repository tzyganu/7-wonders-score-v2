<?php
namespace App\EventSubscriber;

use App\AuthValidator;
use App\Controller\AuthInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AuthSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthValidator
     */
    private $authValidator;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * AuthSubscriber constructor.
     * @param AuthValidator $authValidator
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        AuthValidator $authValidator,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->authValidator = $authValidator;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        /** @var AbstractController[] $controller */
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof AuthInterface) {
            $user = $this->authValidator->getUser();
            if (!$user) {
                $event->setController(function() {
                    return new RedirectResponse($this->urlGenerator->generate('login'));
                });
            }
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}
