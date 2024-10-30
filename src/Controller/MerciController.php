<?php

namespace App\Controller;

use App\Entity\Merci;
use App\Form\MerciType;
use App\Repository\MerciRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class MerciController extends AbstractController
{
    #[Route(name: 'app_merci_index', methods: ['GET'])]
    public function index(MerciRepository $merciRepository): Response
    {
        return $this->render('merci/index.html.twig', [
            'mercis' => $merciRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_merci_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $merci = new Merci();
        $form = $this->createForm(MerciType::class, $merci);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($merci);
            $entityManager->flush();

            return $this->redirectToRoute('app_merci_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('merci/new.html.twig', [
            'merci' => $merci,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_merci_show', methods: ['GET'])]
    public function show(Merci $merci): Response
    {
        return $this->render('merci/show.html.twig', [
            'merci' => $merci,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_merci_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Merci $merci, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MerciType::class, $merci);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_merci_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('merci/edit.html.twig', [
            'merci' => $merci,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_merci_delete', methods: ['POST'])]
    public function delete(Request $request, Merci $merci, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$merci->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($merci);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_merci_index', [], Response::HTTP_SEE_OTHER);
    }
}
