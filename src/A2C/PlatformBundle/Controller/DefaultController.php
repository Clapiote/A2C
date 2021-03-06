<?php
// src/A2C/PlatformBundle/Controller/DefaultController.php

namespace A2C\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use A2C\PlatformBundle\Entity\ContactMessage;
use A2C\PlatformBundle\Form\ContactMessageType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('A2CPlatformBundle:Default:index.html.twig');
    }
    
    public function contactAction(Request $request)
    {
        // Build the form using ContactMessageType class
        $cm  = new ContactMessage();
        $form = $this->get('form.factory')->create(ContactMessageType::class, $cm);
        
        // If there is a client request
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
			//@TODO : send the message
            $request->getSession()->getFlashBag()->add('info', $this->get('translator')->trans('default.contact.flashbag.sent'));
        }
        
        return $this->render('A2CPlatformBundle:Default:contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
