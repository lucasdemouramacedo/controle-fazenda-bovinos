<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalRelatoriosController extends AbstractController
{
    #[Route('/', name: 'relatorio_home', methods: ['GET'])]
    public function relatorioHome(AnimalRepository $animalRepository): Response
    {
        $animalRepository->totalLeite();
        return $this->render('animal/relatorios/relatorio_home.html.twig', [
            'totalLeite' => $animalRepository->totalLeite(),
            'racaoNecessaria' => $animalRepository->racaoNecessaria(),
            'animais' => $animalRepository->animaisUmAno(),
        ]);
    }

    #[Route('/animais', name: 'relatorio_animais', methods: ['GET'])]
    public function relatorioAnimais(AnimalRepository $animalRepository): Response
    {
        return $this->render('animal/relatorios/relatorio_animais.html.twig', [
            'animals' => $animalRepository->findAll(),
        ]);
    }

    #[Route('/abate', name: 'relatorio_animais_para_abate', methods: ['GET'])]
    public function relatorioAnimaisParaAbate(AnimalRepository $animalRepository): Response
    {
        return $this->render('animal/relatorios/relatorio_animais_para_abate.html.twig', [
            'animals' => $animalRepository->findAnimaisParaAbate(),
        ]);
    }

    #[Route('/abatidos', name: 'relatorio_animais_abatidos', methods: ['GET'])]
    public function relatorioAnimaisAbatidos(AnimalRepository $animalRepository): Response
    {
        return $this->render('animal/relatorios/relatorio_animais_abatidos.html.twig', [
            'animals' => $animalRepository->findAnimaisAbatidos(),
        ]);
    }
    #[Route('/new', name: 'app_animal_new-', methods: ['GET', 'POST'])]
    public function new(Request $request, AnimalRepository $animalRepository): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animalRepository->save($animal, true);

            return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('animal/new.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_animal_show-', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return $this->render('animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_animal_edit-', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animal $animal, AnimalRepository $animalRepository): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animalRepository->save($animal, true);

            return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_animal_delete-', methods: ['POST'])]
    public function delete(Request $request, Animal $animal, AnimalRepository $animalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->request->get('_token'))) {
            $animalRepository->remove($animal, true);
        }

        return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
    }
}
