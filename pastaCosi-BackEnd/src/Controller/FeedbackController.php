<?php

namespace App\Controller;

use App\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FeedbackController extends AbstractController
{
    /**
     * @Route("/feedback", name="feedback")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/FeedbackController.php',
        ]);
    }

    /**
     * @Route("/feedback/getById/{id}", name="getFeedbackById", methods={"GET"})
     * @return Response
     *
     */
    public function getById(Feedback $feedback): Response
    {
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $data = $serializer->serialize($feedback, 'json');
        $response = new Response($data, 200);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin',
            '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'DELETE');
        return $response;
    }

    /**
     * @Route("/feedback/getAll/", name="getAllFeedback", methods={"GET"})
     */
    public  function getAllFeedback(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $feedbacks = $em->getRepository(Feedback::class)->findAll();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $data = $serializer->serialize($feedbacks, 'json');
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
     * @Route("/feedback/add/", name="feedback", methods={"post"})
     */

    public function addFeedback(Request $request)
    {
        $data = $request->getContent();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $p = $serializer->deserialize($data, 'App\Entity\Feedback', 'json');
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
     * @Route("/feedback/update/{id}", name="updateFeedback", methods={"put"})
     *
     */
    public  function updateFeedback(Request $request,Feedback $feedback )
    {
        $data = $request->getContent();
        $em= $this->getDoctrine()->getManager();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $pV1 = $serializer->deserialize($data, 'App\Entity\Feedback', 'json');
        $feedback->setComment($pV1->getComment());
        $feedback->setRating($pV1->getRating());
        $em->persist($feedback);
        $em->flush();
        $response = new Response('', Response::HTTP_OK);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        return $response;

    }
    /**
     * @Route("/feedback/delete/{id}", name="deleteFeedback", methods={"delete"})
     * @return Response
     *
     */
    public function deleteFeedback(Feedback $feedback): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($feedback);
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
