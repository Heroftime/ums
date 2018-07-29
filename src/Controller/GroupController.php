<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Groups;
use App\Form\GroupsType;

class GroupController extends Controller
{
    /**
     * @Route("/admin/groups", name="admin_groups")
     */
    public function index()
    {
        $groups = $this->getDoctrine()
        ->getRepository(Groups::class)
        ->findAll();

        return $this->render('group/index.html.twig', [
            'groups' => $groups,
        ]);
    }

    /**
     * @Route("/admin/group/new", name="group_new")
     */
    public function new(Request $request)
    {
        $form = $this->createForm(GroupsType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gData = $form->getData();

            $group = new Groups();
            $group->setName($gData['name']);
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'New group have been added successfully!'
            );
    
            return $this->redirectToRoute('admin_groups');
        }    

        return $this->render('group/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/group/edit/{id}", name="group_edit")
     */
    public function edit(Request $request, $id)
    {
        $group = $this->getDoctrine()
        ->getRepository(Groups::class)
        ->find($id);

        if (!$group) {
            throw $this->createNotFoundException(
                'No group found for id '.$id
            );
        }
      
        $form = $this->createForm(GroupsType::class, $group);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $gData = $form->getData();

            $group->setName($gData->getName());
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Group have been modified successfully!'
            );
    
            return $this->redirectToRoute('admin_groups');
        }    

        return $this->render('group/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/group/delete/{id}", name="group_delete")
     */
    public function delete($id)
    {
        $group = $this->getDoctrine()
            ->getRepository(Groups::class)
            ->find($id);

        if (!$group) {
            throw $this->createNotFoundException(
                'No group found for id '.$id
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($group);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Group have been deleted successfully!'
        );

        return $this->redirectToRoute('admin_groups');
    }
}
