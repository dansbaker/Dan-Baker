<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Utils\ChatRoom;

class AuthController extends Controller
{
    public function indexAction(Request $request)
    {
    	$session = $request->getSession();
    	if($request->get('logout') == 'true')
    	{
    		 $session->invalidate();
    		 return new RedirectResponse('/authenticate/');
   		
    	}
    	else if ($session->get('user_id') == '' && $request->get('email_address') == '')
    	{
    		 return $this->render('AppBundle:Auth:index.html.twig', array(
            		// ...
        	 ));
    	}
    	
    	else if($request->get('email_address') != '' && $request->get('password') != '') //This is a login attempt
        {
             $user_id = $this->get('app.chatroom')->authenticateUser($request->get('email_address'), $request->get('password'));
             if($user_id === false)
             {
                $session->getFlashBag()->add('warning', 'Incorrect Login Credentials');
                return $this->render('AppBundle:Auth:index.html.twig', array(
            		// ...
	        	));
             }
             else
             {
                $session->set('user_id', $user_id);
		     }   
        }   
        return new RedirectResponse('/');
    }

}
