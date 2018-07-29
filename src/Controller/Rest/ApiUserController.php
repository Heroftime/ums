<?php

namespace App\Controller\Rest;

use App\Entity\User;
use App\Entity\Groups;
use Doctrine\ORM\EntityNotFoundException;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiUserController extends FOSRestController
{
    /**
     * @Rest\Get("/users/{userId}")
     */
    public function getUserDetails(int $userId): View
    {
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($userId);

        if (!$user) {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($user, 'json');
        $data = json_decode($jsonContent, true);
        

        return View::create($data, 200);
    }

    /**
     * @Rest\Put("/users/{userId}")
     */
    public function putUser(int $userId, Request $request, UserPasswordEncoderInterface $passwordEncoder): View
    {
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($userId);

        if ($user) {
            $name = $request->get('name');
            if ($name == '') {
                throw new \InvalidArgumentException('Name is a required');
            }

            $password = $request->get('password');
            if ($password == '') {
                throw new \InvalidArgumentException('Password is a required');
            }

            $groupId = $request->get('group');
            if ($groupId == '') {
                throw new \InvalidArgumentException('Group is required');
            }
    
            $group = $this->getDoctrine()
            ->getRepository(Groups::class)
            ->find($groupId);
    
            if (!$group) {
                throw new EntityNotFoundException('Group with id '.$groupId.' does not exist!');
            }

            $user->setName($name);
            $user->setIsAdmin(0);
              
            $password = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($password);

            $user->addGhroup($group);
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
    
            $encoder = new JsonEncoder();
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getName();
            });
    
            $serializer = new Serializer(array($normalizer), array($encoder));
            $jsonContent = $serializer->serialize($user, 'json');
            $user = json_decode($jsonContent, true);

        } else {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }
       
        return View::create($user, 200);
    }

    /**
     * @Rest\Post("/users")
     * @param Request $request
     * @return View
     */
    public function postUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): View
    {
        $name = $request->get('name');
        if ($name == '') {
            throw new \InvalidArgumentException('Name is a required');
        }

        $password = $request->get('password');
        if ($password == '') {
            throw new \InvalidArgumentException('Password is a required');
        }

        $groupId = $request->get('group');
        if ($groupId == '') {
            throw new \InvalidArgumentException('Group is required');
        }

        $group = $this->getDoctrine()
        ->getRepository(Groups::class)
        ->find($groupId);

        if (!$group) {
            throw new EntityNotFoundException('Group with id '.$groupId.' does not exist!');
        }

        $user = new User();
        $user->setName($name);
        $user->setIsAdmin(0);
          
        $password = $passwordEncoder->encodePassword($user, $password);
        $user->setPassword($password);

        $user->addGhroup($group);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();


        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($user, 'json');
        $user = json_decode($jsonContent, true);
        
        return View::create($user, 201);
    }

    /**
     * @Rest\Delete("/users/{userId}")
     */
    public function deleteUser(int $userId): View
    {
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($userId);

        if ($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        } else {
            throw new EntityNotFoundException('User with id '.$userId.' does not exist!');
        }
  
        return View::create([], 204);
    }
}
