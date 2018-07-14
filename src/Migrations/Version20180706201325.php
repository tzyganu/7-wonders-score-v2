<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use App\Entity\Score;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180706201325 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return EntityManagerInterface | object
     */
    private function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    public function up(Schema $schema)
    {
        /** @var Score[] $scores */
        $scores = $this->getEntityManager()->getRepository(Score::class)->findAll();
        //group scores by game
        /** @var Score[][] $grouped */
        $grouped = [];
        foreach ($scores as $score) {
            $grouped[$score->getGame()->getId()][$score->getPosition()] = $score;
        }

        $entityManager = $this->getEntityManager();
        foreach ($scores as $score) {
            $game = $score->getGame();
            $playLeft = $game->getPlayLeft();
            $count = $game->getPlayerCount();
            $leftPosition = (($score->getPosition() - 1  + $count +  (($playLeft) ? 1 : -1)) % $count) + 1;
            $rightPosition = (($score->getPosition() - 1  + $count +  (($playLeft) ? -1 : 1)) % $count) + 1;
            $leftScore = $grouped[$game->getId()][$leftPosition];
            $rightScore = $grouped[$game->getId()][$rightPosition];

            $score->setLeftPlayer($leftScore->getPlayer());
            $score->setLeftSide($leftScore->getSide());
            $score->setLeftwonder($leftScore->getWonder());
            $score->setLeftRank($leftScore->getRank());

            $score->setRightPlayer($rightScore->getPlayer());
            $score->setRightSide($rightScore->getSide());
            $score->setRightWonder($rightScore->getWonder());
            $score->setRightRank($rightScore->getRank());

            $entityManager->persist($score);
        }
        $entityManager->flush();

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
