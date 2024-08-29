<?php
namespace App\Security\Voter;

use App\Entity\Book;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookVoter extends Voter
{
public const EDIT = 'edit';
public const DELETE = 'delete';

protected function supports(string $attribute, mixed $subject): bool
{
return in_array($attribute, [self::EDIT, self::DELETE])
&& $subject instanceof Book;
}

protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
{
$user = $token->getUser();

if (!$user instanceof UserInterface) {
return false;
}

/** @var Book $book */
$book = $subject;

switch ($attribute) {
case self::EDIT:
return $this->canEdit($book, $user);

case self::DELETE:
return $this->canDelete($book, $user);
}

return false;
}

private function canEdit(Book $book, UserInterface $user): bool
{
return $user === $book->getUser() || in_array('ROLE_ADMIN', $user->getRoles());
}

private function canDelete(Book $book, UserInterface $user): bool
{
return $user === $book->getUser() || in_array('ROLE_ADMIN', $user->getRoles());
}
}
