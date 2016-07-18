<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
       $session = $request->getSession();
       
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }

    public function pollmessagesAction(Request $request)
    {
        try
        {
            $messages = $this->get('app.chatroom')->getMessages($request->getSession()->getId());
            $response = new Response(json_encode(Array('type' => 'success', 
                                                       'messages' => $messages)));
        }
        catch(\Exception $e)
        {
            $response = new Response(json_encode(Array('type' => 'error',
                                                       'message' => $e->getMessage())));
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;        
    }
}
