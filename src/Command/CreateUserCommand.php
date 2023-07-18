<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

#[AsCommand(
    name: 'app:create:user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
{

    public function __construct(
        private readonly UserRepository $UserRepository,
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::REQUIRED, 'the login for the user')
            ->addArgument('password', InputArgument::REQUIRED, 'the password for the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $login = $input->getArgument('login');
        $password = $input->getArgument('password');

        if ($login and $password) {
            $io->note(sprintf('the user will be created with login : "%s" | password : "%s"', $login, $password));

            //creation d'un utilisateur
            $user = (new User())
                ->setUsername($login)
                ->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash($password))
                ->setBirthdate(new DateTimeImmutable("1970-01-01"))
                ->setRoles(['ROLE_ADMIN']);

            $this->UserRepository->save($user, true);
           
            $io->success('User created');

            return Command::SUCCESS;
        }
        return Command::FAILURE;
    }
}
