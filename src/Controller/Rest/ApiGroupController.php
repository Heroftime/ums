<?php

namespace App\Controller\Rest;

use App\Entity\Groups;
use Doctrine\ORM\EntityNotFoundException;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiGroupController extends FOSRestController
{
    /**
     * @Rest\Get("/groups/{groupId}")
     */
    public function getGroup(int $groupId): View
    {
        $group = $this->getDoctrine()
        ->getRepository(Groups::class)
        ->find($groupId);

        if (!$group) {
            throw new EntityNotFoundException('Group with id '.$groupId.' does not exist!');
        }

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getName();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));
        $jsonContent = $serializer->serialize($group, 'json');
        $data = json_decode($jsonContent, true);
        

        return View::create($data, 200);
    }

    /**
     * @Rest\Put("/groups/{groupId}")
     */
    public function putGroup(int $groupId, Request $request): View
    {
        $group = $this->getDoctrine()
        ->getRepository(Groups::class)
        ->find($groupId);

        if ($group) {
            $name = $request->get('name');
            if ($name == '') {
                throw new \InvalidArgumentException('Name is a required');
            }

            $group->setName($name);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($group);
            $entityManager->flush();
        } else {
            throw new EntityNotFoundException('Group with id '.$groupId.' does not exist!');
        }
       
        return View::create($group, 200);
    }

    /**
     * @Rest\Post("/groups")
     * @param Request $request
     * @return View
     */
    public function postGroup(Request $request): View
    {
        $name = $request->get('name');
        if ($name == '') {
            throw new \InvalidArgumentException('Name is a required');
        }

        $group = new Groups();
        $group->setName($name);
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($group);
        $entityManager->flush();
        
        return View::create($group, 201);
    }

    /**
     * @Rest\Delete("/groups/{groupId}")
     */
    public function deleteGroup(int $groupId): View
    {
        $group = $this->getDoctrine()
        ->getRepository(Groups::class)
        ->find($groupId);

        if ($group) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($group);
            $entityManager->flush();
        } else {
            throw new EntityNotFoundException('Group with id '.$groupId.' does not exist!');
        }
  
        return View::create([], 204);
    }
}
