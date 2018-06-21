<?php
namespace App\Command;

use App\Entity\User;
use App\Hash;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Doctrine\Common\Persistence\ObjectManager;

class CreateUser extends Command
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
     * @var Hash
     */
    private $hash;

    /**
     * CreateUser constructor.
     * @param ManagerRegistry $managerRegistry
     * @param ObjectManager $objectManager
     * @param Hash $hash
     * @param string $name
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        ObjectManager $objectManager,
        Hash $hash,
        $name = "user:create"
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->objectManager   = $objectManager;
        $this->hash            = $hash;
        parent::__construct($name);
    }

    /**
     * configure command
     */
    protected function configure()
    {
        $this->setName("user:create")
            ->setDescription('Create admin user');
    }

    /**
     * @param $username
     * @return bool
     * @throws \Exception
     */
    private function validateUsername($username)
    {
        if (!$username) {
            throw new \Exception("Username cannot be empty");
        }
        $user = $this->managerRegistry->getRepository(User::class)->findBy(['username' => $username]);
        if ($user) {
            throw new \Exception("User with username {$username} already exists");
        }
        return true;
    }
    /**
     * @param $password
     * @param $rePassword
     * @return bool
     * @throws \Exception
     */
    private function validatePassword($password, $rePassword)
    {
        if (!$password) {
            throw new \Exception("Password cannot be empty");
        }
        if ($password != $rePassword) {
            throw new \Exception("Passwords do not match");
        }
        return true;
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getHelper('question');
        $q = new Question('Pick a username:');
        $username = '';
        while (true) {
            $username = $questionHelper->ask($input, $output, $q);
            try {
                if ($this->validateUsername($username)) {
                    break;
                }
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
            }
        }
        $qp = new Question('Pick a password: ');
        $qp->setHiddenFallback(true);
        $qp->setHidden(true);
        $qrp = new Question('Retype password: ');
        $qrp->setHiddenFallback(true);
        $qrp->setHidden(true);
        $password = '';
        while (true) {
            $password = $questionHelper->ask($input, $output, $qp);
            $rePassword = $questionHelper->ask($input, $output, $qrp);
            try {
                if ($this->validatePassword($password, $rePassword)) {
                    break;
                }
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
            }
        }
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($this->hash->hash($password));
        $user->setActive(1);
        try {
            $this->objectManager->persist($user);
            $this->objectManager->flush();
            $output->writeln("User {$user->getUsername()} was created");
        } catch (\Exception $e) {
            $output->writeln("There was a problem creating the user: ".$e->getMessage());
        }
    }

}
