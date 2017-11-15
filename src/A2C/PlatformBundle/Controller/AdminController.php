<?php
// src/A2C/PlatformBundle/Controller/AdminController.php

namespace A2C\PlatformBundle\Controller;

use A2C\PlatformBundle\Entity\Advert;
use A2C\PlatformBundle\Entity\BannedAddress;
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
			throw new NotFoundHttpException($this->get('translator')->trans('admin.controller.list.noPage', $page));
		}
		
		$nbPerPage = $this->container->getParameter('nb_advert_per_page');
		
		$listAdverts = $this->getDoctrine()
		->getManager()
		->getRepository('A2CPlatformBundle:Advert')
		->getAdverts($page, $nbPerPage);
		
		$nbOfPages = ceil(count($listAdverts) / $nbPerPage);
		
		if ($page > $nbOfPages) {
			throw new NotFoundException($this->get('translator')->trans('admin.controller.list.noPage', $page));
		}
	
		$form = $this->get('form.factory')->create();
		
		return $this->render('A2CPlatformBundle:Admin:list.html.twig', array(
			'listAdverts' => $listAdverts,
			'pageNb' => $nbOfPages,
			'page' => $page,
			'form' => $form->createView())
		);
    }
	
	/**
	* This action will delete an advert.
	* It's call by the delete button in the adverts list.
	*/
	public function deleteAction($request, $id)
	{
		
	}
    
	/**
	* This action will display the list of the banned email addresses, without pagination.
	*/
	public function listBannedEmailAddressAction()
	{
		$listAddresses = $this->getDoctrine()
		->getManager()
		->getRepository('A2CPlatformBundle:BannedAddress')
		->findAll();
		
		$form = $this->get('form.factory')->create();
		
		return $this->render('A2CPlatformBundle:Admin:ban.html.twig', array(
			'listAddresses' => $listAddresses,
			'form' => $form->createView())
		);
	}
	
	/**
	* This action will ban an email address.
	* It's call by the simple form in the banEmail page.
	* The banned address could be unbanned with the unban button
	* in the banned address list.
	*/
    public function banEmailAddressAction() 
    {
        
    }
	
	/**
	* This action will allow a banned email address to post adverts and answers.
	* It's called by the button in the banned email addresses list.
	*/
	public function unbanEmailAddressAction()
	{
		
	}
	
	/**
	* This action will send an email to a subset of the whole website users.
	* It's called by the form on the send broadcast mail page
	*/
	public function sendBroadcastMailAction()
	{
		
	}
}
