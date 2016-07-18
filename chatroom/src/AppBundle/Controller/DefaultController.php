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
        if($session->get('user_id') == '' && $request->get('email_address') == '') //User not currently logged in and this is not a login attempt
        {
             return $this->render('default/login.html.twig');
        }
        else if($request->get('email_address') != '' && $request->get('password') != '') //This is a login attempt
        {
             $user_id = $this->get('app.chatroom')->authenticateUser($request->get('email_address'), $request->get('password'));
             if($user_id === false)
             {
                $session->getFlashBag()->add('warning', 'Incorrect Login Credentials');
                return $this->render('default/login.html.twig');
             }
             else
             {
                $session->set('user_id', $user_id);
             }
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


    public function postmessage(Request $request)
    {
        $session = $request->getSession();
        $session_id = $session->getId();
       // $this->get('app.chatroom')->postMessagesFrom()
        $request->query->get('chat_message');
    }
}
