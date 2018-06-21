<?php
namespace App\Form;

use App\Form\Score\Cash;
use App\Form\Score\Cities;
use App\Form\Score\Civic;
use App\Form\Score\Guild;
use App\Form\Score\Leaders;
use App\Form\Score\Military;
use App\Form\Score\Science;
use App\Form\Score\Trade;
use App\Form\Score\Wonder;
use Symfony\Component\Form\AbstractType;

class ScoreRendererPool
{
    /**
     * @var AbstractType[]
     */
    private $renderers;

    /**
     * @param $renderer
     * @return mixed|AbstractType
     * @throws \Exception
     */
    public function getFormRenderer($renderer)
    {
        $map = $this->getRenderersMap();

        if(isset($map[$renderer])) {
            return $map[$renderer];
        }
        throw new \Exception("Wrong form renderer requested: {$renderer}");
    }

    /**
     * @return array|null|AbstractType[]
     */
    private function getRenderersMap()
    {
        if ($this->renderers === null) {
            $this->renderers = [
                'military' => Military::class,
                'cash' => Cash::class,
                'wonder' => Wonder::class,
                'civic' => Civic::class,
                'trade' => Trade::class,
                'science' => Science::class,
                'guild' => Guild::class,
                'leaders' => Leaders::class,
                'cities' => Cities::class
            ];
        }
        return $this->renderers;
    }
}
