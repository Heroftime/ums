<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Form\UserType;
use App\Entity\User;
use App\Entity\Groups;

class UserController extends Controller
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function index()
    {
        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->getAllUsers();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/new", name="user_new")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();

            $user = new User();
            $user->setName($userData['name']);
            $user->setIsAdmin(0);

            $password = $passwordEncoder->encodePassword($user, $userData['password']);
            $user->setPassword($password);

            // Set the user to the selected group
            $groups = $userData['Groups'];
            foreach ($groups as $g) {
                $user->addGhroup($g);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'New user have been added successfully!'
            );
    
            return $this->redirectToRoute('admin_users');
        }    

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="user_edit")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id)
    {
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }
      
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();
       
            $user->setName($userData->getName());
            $user->setIsAdmin(0);

          
            if ($userData->getPassword() != null) {
                $password = $passwordEncoder->encodePassword($user, $userData->getPassword());
                $user->setPassword($userData->getPassword());
            }

            // Set the user to the selected group
            $groups = $userData->getGroups();
            foreach ($groups as $g) {
                $user->addGhroup($g);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'User have been modified successfully!'
            );
    
            return $this->redirectToRoute('admin_users');
        }    

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/show/{id}", name="user_show")
     */
    public function show($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="user_delete")
     */
    public function delete($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'User have been deleted successfully!'
        );

        return $this->redirectToRoute('admin_users');
    }

    
}
