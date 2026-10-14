<?php

declare(strict_types=1);

namespace App\Account\Infrastructure\Profile;

use App\Account\Domain\ProfileDirectoryInterface;
use App\Account\Domain\ProfileReference;
use App\Account\Domain\ProfileType;
use App\Admin\Domain\AdminId;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Coach\Domain\CoachId;
use App\Coach\Domain\Repository\CoachRepositoryInterface;
use App\Player\Domain\PlayerId;
use App\Player\Domain\Repository\PlayerRepositoryInterface;

/**
 * Adaptateur (anti-corruption) : traduit "ce profil existe ?" vers les repositories
 * des contextes Player / Coach / Admin.
 */
final readonly class RepositoryProfileDirectory implements ProfileDirectoryInterface
{
    public function __construct(
        private PlayerRepositoryInterface $players,
        private CoachRepositoryInterface $coaches,
        private AdminRepositoryInterface $admins,
    ) {
    }

    public function exists(ProfileReference $profile): bool
    {
        try {
            return match ($profile->type()) {
                ProfileType::PLAYER => null !== $this->players->findById(PlayerId::fromString($profile->identifier())),
                ProfileType::COACH => null !== $this->coaches->findById(CoachId::fromString($profile->identifier())),
                ProfileType::ADMIN => null !== $this->admins->findById(AdminId::fromString($profile->identifier())),
            };
        } catch (\InvalidArgumentException) {
            return false;
        }
    }
}
