<?php
namespace App\FormWrapper;

use App\FormWrapper;
use App\Button\Factory as ButtonFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Factory
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var \App\Button\Factory
     */
    private $buttonFactory;

    /**
     * @param \Twig_Environment $twig
     * @param FormFactoryInterface $formFactory
     * @param UrlGeneratorInterface $router
     * @param ButtonFactory $buttonFactory
     */
    public function __construct(
        \Twig_Environment $twig,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        ButtonFactory $buttonFactory
    ) {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->buttonFactory = $buttonFactory;
    }

    /**
     * @param array $data
     * @return FormWrapper
     * @throws \Exception
     */
    public function create(array $data = [])
    {
        if (!isset($data['form']['class'])) {
            throw new \Exception('Missing form class');
        }
        $formClass = $data['form']['class'];
        unset($data['form']['class']);
        $form = $this->formFactory->create($formClass, null, $this->prepareOptions($data['form']));
        $buttons = [];
        if (isset($config['buttons'])) {
            foreach ($config['buttons'] as $id => $buttonData) {
                $buttons[$id] = $this->buttonFactory->create($buttonData);
            }
        }
        $options = $data['options'] ?? [];
        return new FormWrapper($this->twig, $form, $options, $buttons);
    }

    /**
     * @param $formOptions
     * @return mixed
     */
    private function prepareOptions($formOptions)
    {
        if (isset($formOptions['action'])) {
            $params = $formOptions['actionParams'] ?? [];
            $formOptions['action'] = $this->router->generate($formOptions['action'], $params);
            unset($formOptions['actionParams']);
        }
        return $formOptions;
    }
}
