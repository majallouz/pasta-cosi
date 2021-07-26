<?php

namespace App\Controller;


use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/EventController.php',
        ]);
    }

    /**
     * @Route("/event/getById/{id}", name="getById", methods={"GET"})
     * @return Response
     *
     */
    public function getById(Event $event): Response
    {
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $data = $serializer->serialize($event, 'json');
        $response = new Response($data, 200);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin',
            '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'DELETE');
        return $response;
    }

    /**
     * @Route("/event/getAll/", name="getAllEvent", methods={"GET"})
     */
    public  function getAllEvent(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository(Event::class)->findAll();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $data = $serializer->serialize($events, 'json');
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
     * @Route("/event/add/", name="event", methods={"post"})
     */

    public function addEvent(Request $request)
    {
        $data = $request->getContent();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $p = $serializer->deserialize($data, 'App\Entity\Event', 'json');
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
     * @Route("/event/update/{id}", name="updateEvent", methods={"put"})
     *
     */
    public  function updateEvent(Request $request,Event $event )
    {
        $data = $request->getContent();
        $em= $this->getDoctrine()->getManager();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $pV1 = $serializer->deserialize($data, 'App\Entity\Event', 'json');
        $event->setName($pV1->getName());
        $event->setDescription($pV1->getDescription());
        $em->persist($event);
        $em->flush();
        $response = new Response('', Response::HTTP_OK);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        return $response;

    }
    /**
     * @Route("/event/delete/{id}", name="delete", methods={"delete"})
     * @return Response
     *
     */
    public function deleteEvent(Event $event): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
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
