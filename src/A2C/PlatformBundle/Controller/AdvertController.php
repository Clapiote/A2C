<?php

// src/A2C/PlatformBundle/Controller/AdvertController.php

namespace A2C\PlatformBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use A2C\PlatformBundle\Entity\Advert;
use A2C\PlatformBundle\Entity\User;
use A2C\PlatformBundle\Form\AdvertType;

/**
 * Description of AdvertController
 *
 * @author Vincent
 */
class AdvertController extends Controller
{

    public function indexAction()
    {
        
    }

    public function viewAction()
    {
        
    }

    public function createAction(Request $request)
    {
        $advert = new Advert();
        $form = $this->get('form.factory')->create(AdvertType::class, $advert);
        
        if ($request->isMethod('POST') && $form->handleRequest()->isValid()) {
            // Save the advert in the DB
            // @TODO : call the services of antispam and advert verification
            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('info', $this->get('translator')->trans('advert.create.ok'));
            $this->redirectToRoute('a2c_platform_index');
        }
        
        return $this->render('A2CPlatformBundle:Advert:createAdvert.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteAction()
    {
        
    }

}
