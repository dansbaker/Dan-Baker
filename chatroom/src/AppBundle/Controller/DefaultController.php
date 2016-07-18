<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Utils\ChatRoom;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        
        $session = $request->getSession();
        if($session->get('user_id') == '')
        {
            return new RedirectResponse('/authenticate/');
        }
        
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'start_time' => date('Y-m-d H:i:s')
        ]);
    }

    public function pollmessagesAction(Request $request)
    {
        try
        {
            $messages = $this->get('app.chatroom')->getMessagesSince($request->query->get('last_message_time'));
            $response = new Response(json_encode(Array('type' => 'success', 
                                                       'messages' => $messages)));
        }
        catch(\Exception $e)
        {
            $response = new Response(json_encode(Array('type' => 'error',
                                                       'error_message' => $e->getMessage())));
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;        
    }


    public function postmessageAction(Request $request)
    {
        $session = $request->getSession();
        if($session->get('user_id') == '') //Prevent unauthenticated users from posting messages;
        {
            $response = new Response(json_encode(Array('type' => 'error',
                                                       'error_message' => 'Not authenticated to post messages')));
            return $response;
        }
        
        try
        {
            $this->get('app.chatroom')->postMessage($session->get('user_id'), $request->get('message_content'));
            $response = new Response(json_encode(Array('type' => 'success')));
        }
        catch(\Exception $e)
        {
            $response = new Response(json_encode(Array('type' => 'error',
                                                       'error_message' => $e->getMessage())));
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;        
    }
}
