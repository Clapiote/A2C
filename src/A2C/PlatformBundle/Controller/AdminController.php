<?php
// src/A2C/PlatformBundle/Controller/AdminController.php

namespace A2C\PlatformBundle\Controller;

use A2C\PlatformBundle\Entity\Advert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * This controller manage all administrations operations :
 * - list adverts
 * - delete adverts
 * - ban email address
 * - send email to all users
 * @author Vincent
 */
class AdminController extends Controller 
{
	
	/**
	* Used to translate text.
	* Constructed by getting the translator service.
	*/
	private $translator;
	
	public function __construct()
	{
		$this->translator = $this->get('translator');
	}
	
    /**
     * This action will render a default view, with only the menu.
     * @TODO : keep this action or call directly listAction ?
     */
    public function indexAction()
    {
        return $this->render('A2CPlatformBundle:Admin:index.html.twig');
    }
    
    /**
     * This action will render the view in order to list all the adverts 
     * It will :
     * * fetch the adverts from the DB
     * * pass the adverts to the view admin.html.twig
     */
    public function listAction($page) 
    {
		if ($page < 1) {
			throw new NotFoundHttpException($translator->trans('admin.controller.list.noPage', $page));
		}
		
		
		return $this->render('A2CPlatformBundle:Admin:list.html.twig', array(
			'listAdvert' => $listAdvert,
			'pageNb' => $pageNb,
			'page' => $page)
		);
    }
	
	/**
	* This action will delete an advert.
	* It's call by the delete button in the adverts list.
	*/
	public function deleteAction($request, $id)
	{
		
	}
    
    public function banEmailAddressAction() 
    {
        
    }
	
	public function sendMailAction()
	{
		
	}
}
