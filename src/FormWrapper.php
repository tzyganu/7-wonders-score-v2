<?php
namespace App;

use App\Button;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormWrapper
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var Button[]
     */
    private $buttons = [];
    /**
     * @var string
     */
    private $template = 'crud/form.html.twig';
    /**
     * @var
     */
    private $form;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $formView;

    /**
     * @param \Twig_Environment $twig
     * @param FormInterface $form
     * @param array $options
     * @param array $buttons
     */
    public function __construct(
        \Twig_Environment $twig,
        FormInterface $form,
        array $options = [],
        array $buttons = []
    ) {
        $this->twig = $twig;
        $this->form = $form;
        $this->buttons = $buttons;
        foreach ($this->getConstructOptionFields() as $field) {
            if (isset($options[$field])) {
                $method = 'set'.ucfirst($field);
                $this->$method($options[$field]);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getFormView()
    {
        if ($this->formView === null) {
            $this->formView = $this->form->createView();
        }
        return $this->formView;
    }

    private function getConstructOptionFields()
    {
        return ['title', 'template'];
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $id
     * @param Button $button
     */
    public function addButton($id, Button $button)
    {
        $this->buttons[$id] = $button;
    }

    /**
     * @return array
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    public function setData($data)
    {
        $this->getForm()->setData($data);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->twig->render(
            $this->getTemplate(),
            [
                'formWrapper' => $this,
            ]
        );
    }
}
