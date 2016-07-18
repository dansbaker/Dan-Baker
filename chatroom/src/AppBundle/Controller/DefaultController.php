<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Utils\ChatRoom;

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
            'start_time' => date('d/m/Y H:i:s')
        ]);
    }

    public function pollmessagesAction(Request $request)
    {

         $messages = $this->get('app.chatroom')->getMessages();
        try
        {
            $messages = $this->get('app.chatroom')->getMessages();
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
