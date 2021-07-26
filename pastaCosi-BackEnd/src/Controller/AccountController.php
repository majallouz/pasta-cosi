<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Account;
class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     * 
     * 
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AccountController.php',
        ]);
    }

/**
     * @Route("/login", name="login", methods={"POST"})
     */
  /*  public function login(Request $request){        
        
        $helpers = $this->get(Helpers::class);
        
        if ($json = $request->getContent()) {
            $parametersAsArray = json_decode($json, true);

            
            $email = ($parametersAsArray['email']) ? $parametersAsArray['email'] : null ;
            $password = ($parametersAsArray['password']) ? $parametersAsArray['password'] : null ;
            $getHash = null ;

            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "This email is not valid!";
            $validate_email = $this->get('validator')->validate($email, $emailConstraint);

            $pwd = hash('sha256', $password);

            if(count($validate_email) == 0 && $password != null){

                $jwt_auth = $this->get(JwtAuth::class);

                if($getHash == null || $getHash == false){
                    $signup = $jwt_auth->signup($email, $pwd);
                }else{
                    $signup = $jwt_auth->signup($email, $pwd, true);
                }

                return $this->json($signup);

            }else{
                $data = array(
                    'status'=>'error',
                    'data'=>'Email or password Incorrect.'
                );
            }
        }else{
            $data = array(
                'status'=>'error',
                'data'=>'Send json via post.'
            );
    
        }

        return $helpers->json($data);
    }
*/

    /**
* @Route("/account/getById/{id}", name="getAccountById", methods={"GET"})
* @return Response
*
*/
public function getById(Account $account): Response
{
    $encoders = array(new JsonEncoder());
    $serializer = new Serializer([new ObjectNormalizer()], $encoders);
    $data = $serializer->serialize($account, 'json');
    $response = new Response($data, 200);
   //Allow all websites
   $response->headers->set('Access-Control-Allow-Origin', 
   '*');
   // You can set the allowed methods too, if you want
   $response->headers->set('Access-Control-Allow-Methods', 'DELETE');
   return $response;
}



    /**
* @Route("account/getAll/", name="getAllAccount", methods={"GET"})
*/
public  function getAllAccount(): Response
{
    $em = $this->getDoctrine()->getManager();	
   $accounts = $em->getRepository(Account::class)->findAll();
   $encoders = array(new JsonEncoder());
   $serializer = new Serializer([new ObjectNormalizer()], $encoders);
   $data = $serializer->serialize($accounts, 'json');
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
* @Route("/account/add/", name="account", methods={"post"})
*/

public function addAccount(Request $request)
{
   $data = $request->getContent();
   $encoders = array(new JsonEncoder());
   $serializer = new Serializer([new ObjectNormalizer()], $encoders);
   $p = $serializer->deserialize($data, 'App\Entity\Account', 'json');
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
* @Route("/account/update/{id}", name="updateAcount", methods={"put"})
*
*/
public  function updateAccount(Request $request,Account $account )
{
    $data = $request->getContent();
    $em= $this->getDoctrine()->getManager();
    $encoders = array(new JsonEncoder());
    $serializer = new Serializer([new ObjectNormalizer()], $encoders);
    $pV1 = $serializer->deserialize($data, 'App\Entity\Account', 'json');
    $account->setNom($pV1->getNom());
    $account->setPrenom($pV1->getPrenom());
    $account->setEmail($pV1->getEmail());
    $account->setAge($pV1->getAge());
    $account->setPassword($pV1->getPassword());
    $em->persist($account);
    $em->flush();
    $response = new Response('', Response::HTTP_OK);
    //Allow all websites
    $response->headers->set('Access-Control-Allow-Origin', '*');
    // You can set the allowed methods too, if you want
    $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
    return $response;
 
}


/**
* @Route("/account/delete/{id}", name="deleteAccount", methods={"delete"})
* @return Response
*
*/
public function deleteAccount(Account $account): Response
{
   $em = $this->getDoctrine()->getManager();
   $em->remove($account);
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
