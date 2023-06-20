<?php

namespace App\Security\Voter;

use App\Entity\Pokemon;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PokemonVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const DEL = 'DEL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DEL])
            && $subject instanceof Pokemon;
    }

    /**
     * @param Pokemon $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$subject->isLegendary()) {
            return true;
        }

        return false;
    }
}
