<?php

namespace App\Controller;

use App\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */

     //cyrine_test
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PaymentController.php',
        ]);
    }

    /**
     * @Route("/payment/getById/{id}", name="getById", methods={"GET"})
     * @return Response
     *
     */
    public function getById(Payment $payment): Response
    {
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $data = $serializer->serialize($payment, 'json');
        $response = new Response($data, 200);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin',
            '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'DELETE');
        return $response;
    }

    /**
     * @Route("/payment/getAll/", name="getAllPayment", methods={"GET"})
     */
    public  function getAllPayment(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $payments = $em->getRepository(Payment::class)->findAll();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $data = $serializer->serialize($payments, 'json');
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
     * @Route("/payment/add/", name="payment", methods={"post"})
     */

    public function addPayment(Request $request)
    {
        $data = $request->getContent();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $p = $serializer->deserialize($data, 'App\Entity\Payment', 'json');
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
     * @Route("/payment/update/{id}", name="updatePayment", methods={"put"})
     *
     */
    public  function updatePayment(Request $request,Payment $payment )
    {
        $data = $request->getContent();
        $em= $this->getDoctrine()->getManager();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $pV1 = $serializer->deserialize($data, 'App\Entity\Payment', 'json');
        $payment->setTotalPrice($pV1->getTotalPrice());
        $payment->setPaidPrice($pV1->getPaidPrice());
        $em->persist($payment);
        $em->flush();
        $response = new Response('', Response::HTTP_OK);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
        return $response;

    }
    /**
     * @Route("/payment/delete/{id}", name="delete", methods={"delete"})
     * @return Response
     *
     */
    public function deletePayment(Payment $payment): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($payment);
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
