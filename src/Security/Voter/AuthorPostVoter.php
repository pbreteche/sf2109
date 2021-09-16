<?php

namespace App\Security\Voter;

use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthorPostVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return 'POST_EDIT' === $attribute
            && $subject instanceof Post;
    }

    /**
     * @param Post $post
     */
    protected function voteOnAttribute(string $attribute, $post, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $user === $post->getWrittenBy();
    }
}
