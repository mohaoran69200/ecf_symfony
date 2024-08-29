<?php

namespace App\Security\Voter;

use App\Entity\Author;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthorVoter extends Voter
{
public const EDIT = 'POST_EDIT';
public const VIEW = 'POST_VIEW';

protected function supports(string $attribute, mixed $subject): bool
{
return in_array($attribute, [self::EDIT, self::VIEW])
&& $subject instanceof Author;
}

protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
{
$user = $token->getUser();

if (!$user instanceof UserInterface) {
return false;
}

/** @var Author $author */
$author = $subject;

switch ($attribute) {
case self::EDIT:
// Logic to determine if the user can EDIT
// For example, only the author or an admin can edit
return $this->canEdit($author, $user);

case self::VIEW:
// Logic to determine if the user can VIEW
// For example, anyone can view, or maybe only the author and admins
return $this->canView($author, $user);
}

return false;
}

private function canEdit(Author $author, UserInterface $user): bool
{
// Example logic: only the author themselves or an admin can edit
// Adjust this logic based on your needs
return $user === $author->getUser() || in_array('ROLE_ADMIN', $user->getRoles());
}

private function canView(Author $author, UserInterface $user): bool
{
// Example logic: everyone can view, or adjust based on your needs
return true;
}
}
