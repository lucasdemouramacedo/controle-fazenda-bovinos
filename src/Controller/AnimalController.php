<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/animal')]
class AnimalController extends AbstractController
{
    #[Route('/', name: 'app_animal_indexx', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository): Response
    {

        return $this->render('animal/index.html.twig', [
            'animals' => $animalRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_animal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnimalRepository $animalRepository): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animalRepository->save($animal, true);

            $this->addFlash('notice', 'Cadastro efetuado com sucesso!');
            return $this->redirectToRoute('relatorio_animais', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('animal/new.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_animal_show', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return $this->render('animal/show.html.twig', [
            'animal' => $animal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_animal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animal $animal, AnimalRepository $animalRepository): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animalRepository->save($animal, true);

            $this->addFlash('notice', 'Atualização efetuada com sucesso!');
            return $this->redirectToRoute('relatorio_animais', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_animal_delete', methods: ['POST'])]
    public function delete(Request $request, Animal $animal, AnimalRepository $animalRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $animal->getId(), $request->request->get('_token'))) {
            $this->addFlash('notice', 'Registro deletado!');
            $animalRepository->remove($animal, true);
        }

        return $this->redirectToRoute('relatorio_animais', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/abate', name: 'app_animal_abate', methods: ['GET', 'POST'])]
    public function abate(Request $request, Animal $animal, AnimalRepository $animalRepository): Response
    {
        //dd($animal);
        try {

            if ($this->isCsrfTokenValid('abate' . $animal->getId(), $request->request->get('_token'))) {
                
                $animal->atualizaStatus();
                $animalRepository->save($animal, true);
                $this->addFlash('notice', 'Animal abatido!');
                return $this->redirectToRoute('relatorio_animais_para_abate', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('notice', 'Ocorreu algum erro!');
            return $this->redirectToRoute('relatorio_animais_para_abate', [], Response::HTTP_SEE_OTHER);
        } catch (Exception $e) {
            dd($e);
            $this->addFlash('notice', 'Ocorreu um erro, tente novamente!');
            return $this->redirectToRoute('relatorio_animais_para_abate', [], Response::HTTP_SEE_OTHER);
        }
    }
}
