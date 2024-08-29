<?php
namespace App\Controller;

use App\Form\BookType;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/book', name: 'app_book_')]
class BookController extends AbstractController
{
#[Route('/new', name: 'new')]
#[IsGranted('ROLE_USER')]
public function new(
Request $request,
EntityManagerInterface $entityManager
): Response {
$book = new Book();
$form = $this->createForm(BookType::class, $book);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$book->setUser($this->getUser())
->setCreatedAt(new \DateTimeImmutable())
->setUpdatedAt(new \DateTimeImmutable());

$entityManager->persist($book);
$entityManager->flush();

$this->addFlash('success', 'Livre ajouté avec succès.');
return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
} elseif ($form->isSubmitted()) {
$this->addFlash('error', 'Le formulaire contient des erreurs.');
}

return $this->render('book/new.html.twig', [
'form' => $form->createView(),
]);
}

#[Route('/update/{id}', name: 'update')]
#[IsGranted('ROLE_USER')]
public function edit(
Book $book,
Request $request,
EntityManagerInterface $entityManager
): Response {
$this->denyAccessUnlessGranted('edit', $book);

$form = $this->createForm(BookType::class, $book);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$book->setUpdatedAt(new \DateTimeImmutable());
$entityManager->flush();
$this->addFlash('success', 'Livre mis à jour avec succès.');
return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
}

return $this->render('book/new.html.twig', [
'form' => $form->createView(),
'book' => $book,
]);
}

#[Route('/show/{id}', name: 'show')]
public function show(Book $book): Response
{
return $this->render('book/show.html.twig', [
'book' => $book,
]);
}

#[Route('/remove/{id}', name: 'remove')]
#[IsGranted('ROLE_USER')]
public function remove(Book $book, EntityManagerInterface $entityManager): Response
{
$this->denyAccessUnlessGranted('delete', $book);

$entityManager->remove($book);
$entityManager->flush();
$this->addFlash('success', 'Livre supprimé avec succès.');

return $this->redirectToRoute('app_home');
}
}
