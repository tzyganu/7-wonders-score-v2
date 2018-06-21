<?php
namespace App\Command;

use App\Entity\Achievement;
use App\Entity\AchievementColor;
use App\Entity\AchievementGroup;
use App\Entity\Category;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\PlayerAchievement;
use App\Entity\Score;
use App\Entity\User;
use App\Entity\Wonder;
use App\Entity\WonderSet;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ResetDb extends Command
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * CreateUser constructor.
     * @param ObjectManager $objectManager
     * @param string $name
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        ObjectManager $objectManager,
        $name = "app:db:reset"
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->objectManager = $objectManager;
        parent::__construct($name);
    }
    protected function configure()
    {
        $this->setName("app:db:reset")
            ->setDescription('Reset database. (for dev purposes only)');
        $this->addOption('include-player', 'p', InputOption::VALUE_OPTIONAL, "Drop players table also", null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getHelper('question');
        $q = new Question('Confirm db cleanup (y/n):');
        $confirm = $questionHelper->ask($input, $output, $q);
        if (strtolower($confirm) !== 'y') {
            return;
        }
        $withPlayer = $input->getOption('include-player');
        $tables = $this->getTablesToDrop($withPlayer);
        $connection = $this->managerRegistry->getConnection();
        $sql = "DROP TABLE {$tables}";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * @param bool $withPlayer
     * @return array
     */
    private function getEntitiesToDrop($withPlayer = false)
    {
        $entities = [
            PlayerAchievement::class,
            Score::class,
            Achievement::class,
            AchievementColor::class,
            AchievementGroup::class,
            Wonder::class,
            WonderSet::class,
            Category::class,
            Game::class,
            User::class
        ];
        if ($withPlayer) {
            $entities[] = Player::class;
        }
        return $entities;
    }

    /**
     * @param bool $withPlayer
     * @return string
     */
    private function getTablesToDrop($withPlayer = false)
    {
        $toDrop = [];
        foreach ($this->getEntitiesToDrop($withPlayer) as $entity) {
             $toDrop[] = $this->objectManager->getClassMetadata($entity)->table['name'];
        }
        $toDrop[] = 'migration_versions';
        return implode(',', $toDrop);
    }
}
