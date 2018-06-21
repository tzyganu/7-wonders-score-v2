<?php
namespace App\FormWrapper;

use App\Button\Factory as ButtonFactory;
use App\FormWrapper;
use App\YamlLoader;
use Symfony\Component\Form\FormInterface;

class Loader
{
    /**
     * @var Factory
     */
    private $formWrapperFactory;
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $fileLocation;
    /**
     * @var \App\Button\Factory
     */
    private $buttonFactory;

    /**
     * @param Factory $formFactory
     * @param ButtonFactory $buttonFactory
     * @param YamlLoader $yamlLoader
     * @param string $fileLocation
     */
    public function __construct(
        Factory $formFactory,
        ButtonFactory $buttonFactory,
        YamlLoader $yamlLoader,
        $fileLocation = '../config/form/'
    ) {
        $this->formWrapperFactory   = $formFactory;
        $this->buttonFactory = $buttonFactory;
        $this->yamlLoader    = $yamlLoader;
        $this->fileLocation  = $fileLocation;
    }

    /**
     * @param $name
     * @param $formData
     * @return FormWrapper
     */
    public function loadForm($name, $formData)
    {
        $config = $this->yamlLoader->load($this->locateFormConfig($name));
        $config['form']['data'] = $formData;
        return $this->formWrapperFactory->create($config);
    }

    /**
     * @param $name
     * @return string
     */
    private function locateFormConfig($name)
    {
        return $this->fileLocation.$name.'.yml';
    }

    /**
     * @param $config
     * @param $formData
     * @return FormWrapper
     */
    private function buildForm($config, $formData)
    {
        $options = isset($config['options']) ? $config['options'] : [];
        $options['data'] = $formData;
        $formWrapper = $this->formWrapperFactory->create($options);
        if (isset($config['buttons'])) {
            foreach ($config['buttons'] as $id => $buttonData) {
                $formWrapper->addButton($id, $this->buttonFactory->create($buttonData));
            }
        }
        return $formWrapper;
    }
}
