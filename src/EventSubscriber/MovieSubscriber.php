<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use function array_map;
use function dump;
use function implode;
use function sprintf;

class MovieSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function notifyAllAdmins(MovieAddedEvent $movieAddedEvent): void
    {
        $allAdmins = $this->userRepository->listAllAdmins();

        dump(sprintf(
            '%s user created the movie %s from %d on the %s. Will notify all those admins : %s.',
            $movieAddedEvent->user->getUserIdentifier(),
            $movieAddedEvent->movie->getTitle(),
            $movieAddedEvent->movie->getReleasedAt()->format('Y'),
            $movieAddedEvent->createdAt->format('d/m/Y'),
            implode(', ', array_map(fn(User $user): string => $user->getUserIdentifier(), $allAdmins))
        ));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MovieAddedEvent::class => [
                ['notifyAllAdmins', 0],
            ],
        ];
    }
}
