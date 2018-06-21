<?php
namespace App\Button;

use App\Button;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Factory
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    function __construct(
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param array $data
     * @return Button
     */
    public function create(array $data = [])
    {
        return new Button(
            $this->urlGenerator,
            $data
        );
    }
}
