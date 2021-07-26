<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;


class ActivityCtrlController extends AbstractController
{
    /**
     * @Route("/activity/ctrl", name="activity_ctrl")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ActivityCtrlController.php',
        ]);
    }



/**
* @Route("/activity/getById/{id}", name="getActivityById", methods={"GET"})
* @return Response
*
*/
public function getById(Activity $activity): Response
{
    $encoders = array(new JsonEncoder());
    $serializer = new Serializer([new ObjectNormalizer()], $encoders);
    $data = $serializer->serialize($activity, 'json');
    $response = new Response($data, 200);
   //Allow all websites
   $response->headers->set('Access-Control-Allow-Origin', 
   '*');
   // You can set the allowed methods too, if you want
   $response->headers->set('Access-Control-Allow-Methods', 'DELETE');
   return $response;
}



    /**
* @Route("activity/getAll/", name="getAllActivity", methods={"GET"})
*/
public  function getAllActivity(): Response
{
    $em = $this->getDoctrine()->getManager();	
   $activities = $em->getRepository(Activity::class)->findAll();
   $encoders = array(new JsonEncoder());
   $serializer = new Serializer([new ObjectNormalizer()], $encoders);
   $data = $serializer->serialize($activities, 'json');
   $response = new Response($data, 200);
   //content type
   $response->headers->set('Content-Type', 'application/json');
   //Allow all websites
   $response->headers->set('Access-Control-Allow-Origin', '*');
   // You can set the allowed methods too, if you want
   $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
   return $response;
}


/**
* @Route("/activity/add/", name="addactivity", methods={"post"})
*/

public function addActivity(Request $request)
{
   $data = $request->getContent();
   $encoders = array(new JsonEncoder());
   $serializer = new Serializer([new ObjectNormalizer()], $encoders);
   $p = $serializer->deserialize($data, 'App\Entity\Activity', 'json');
   $em= $this->getDoctrine()->getManager();
   $em->persist($p);
   $em->flush();
   $response = new Response('', Response::HTTP_CREATED);
   //Allow all websites
   $response->headers->set('Access-Control-Allow-Origin', '*');
   // You can set the allowed methods too, if you want
   $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
   return $response;
}

/**
* @Route("/activity/update/{id}", name="updateActivity", methods={"put"})
*
*/
public  function updateActivity(Request $request,Activity $activity )
{
    $data = $request->getContent();
    $em= $this->getDoctrine()->getManager();
    $encoders = array(new JsonEncoder());
    $serializer = new Serializer([new ObjectNormalizer()], $encoders);
    $pV1 = $serializer->deserialize($data, 'App\Entity\Activity', 'json');
    $activity->setTitle($pV1->getTitle());
    $activity->setDescription($pV1->getDescription());
    $em->persist($activity);
    $em->flush();
    $response = new Response('', Response::HTTP_OK);
    //Allow all websites
    $response->headers->set('Access-Control-Allow-Origin', '*');
    // You can set the allowed methods too, if you want
    $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
    return $response;
 
}


/**
* @Route("/activity/delete/{id}", name="deleteActivity", methods={"delete"})
* @return Response
*
*/
public function deleteActivity(Activity $activity): Response
{
   $em = $this->getDoctrine()->getManager();
   $em->remove($activity);
   $em->flush();
   $response = new Response('', Response::HTTP_OK);
   //Allow all websites
   $response->headers->set('Access-Control-Allow-Origin', 
   '*');
   // You can set the allowed methods too, if you want
   $response->headers->set('Access-Control-Allow-Methods', 'DELETE');
   return $response;
}


}
