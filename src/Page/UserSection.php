<?php
namespace App\Page;

use App\AuthValidator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserSection
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
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $template = 'user_section.html.twig';

    /**
     * UserSection constructor.
     * @param AuthValidator $authValidator
     * @param UrlGeneratorInterface $urlGenerator
     * @param \Twig_Environment $twig
     * @param string $template
     */
    public function __construct(
        AuthValidator $authValidator,
        UrlGeneratorInterface $urlGenerator,
        \Twig_Environment $twig,
        $template = null
    ) {
        $this->authValidator = $authValidator;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
        if ($template !== null) {
            $this->template = $template;
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->twig->render($this->template, ['user' => $this->authValidator->getUser()]);
    }

}
