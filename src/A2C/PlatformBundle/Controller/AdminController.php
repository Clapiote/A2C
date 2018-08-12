<?php

// src/A2C/PlatformBundle/Controller/AdminController.php

namespace A2C\PlatformBundle\Controller;

use A2C\PlatformBundle\Entity\Advert;
use A2C\PlatformBundle\Entity\BannedAddress;
use A2C\PlatformBundle\Entity\BroadcastMessage;
use A2C\PlatformBundle\Form\BannedAddressType;
use A2C\PlatformBundle\Form\SendBroadcastMailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    const NB_PER_PAGE = 3;

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
            throw new NotFoundHttpException($this->get('translator')->trans('admin.controller.list.noPage', array('%page%' => $page)));
        }

        $listAdverts = $this->getDoctrine()
                ->getManager()
                ->getRepository('A2CPlatformBundle:Advert')
                ->getAdverts($page, self::NB_PER_PAGE);

        if (count($listAdverts) == 0) {
            return $this->render('A2CPlatformBundle:Admin:list.html.twig', array(
                        'listAdverts' => $listAdverts,
                        'pageNb' => 1,
                        'page' => 1)
            );
        }

        $nbOfPages = ceil(count($listAdverts) / self::NB_PER_PAGE);

        if ($page > $nbOfPages) {
            throw new NotFoundHttpException($this->get('translator')->trans('admin.controller.list.noPage', array('%page%' => $page)));
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
     * @ParamConverter("advert", class="A2CPlatformBundle:Advert")
     */
    public function deleteAction(Request $request, Advert $advert)
    {
        $em = $this->getDoctrine()->getManager();

        // Empty form, just to handle CSRF token
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', $this->get('translator')->trans('admin.list.flashbag.deleted'));
        }

        return $this->redirectToRoute('a2c_platform_admin_list');
    }

    /**
     * This action will display the list of the banned email addresses, without pagination.
     * It also handles the request to ban an email address.
     */
    public function listBannedEmailAddressAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Create new form
        $ba = new BannedAddress();
        $formBan = $this->get('form.factory')->create(BannedAddressType::class, $ba);

        // Empty form, just to handle CSRF token
        $formUnban = $this->get('form.factory')->create();

        // If there is a client request
        if ($request->isMethod('POST') && $formBan->handleRequest($request)->isValid()) {
            //Fetch the address from post request
            $params = $request->request->all();
            $bannedAddress = new BannedAddress();
            //@TODO : verify post data
            $addressToBan = $params['banned_address']['emailAddress'];
            $bannedAddress->setEmailAddress($addressToBan);

            // Save in database
            $em->persist($bannedAddress);
            $em->flush();

            $request->getSession()->getFlashBag()
                    ->add('info', $this->get('translator')
                            ->trans('admin.ban.flashbag.banned', array('%address%' => $addressToBan)));
        }

        $listAddresses = $em->getRepository('A2CPlatformBundle:BannedAddress')->findAll();

        return $this->render('A2CPlatformBundle:Admin:ban.html.twig', array(
                    'listAddresses' => $listAddresses,
                    'formBan' => $formBan->createView(),
                    'formUnban' => $formUnban->createView())
        );
    }

    /**
     * ! Replaced by listBAnnedEmailAddressAction !
     * This action will ban an email address.
     * It's call by the simple form in the banEmail page.
     * The banned address could be unbanned with the unban button
     * in the banned address list.
     */
    /* public function banEmailAddressAction(Request $request)
      {
      // Build the form using BanEmailAddressType class
      $ba = new BannedAddress();
      $form = $this->get('form.factory')->create(BannedAddressType::class, $ba);

      // If there is a client request
      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      //@TODO : ban the address
      $request->getSession()->getFlashBag()->add('info', $this->get('translator')->trans('admin.ban.flashbag.banned'));
      }

      return $this->render('A2CPlatformBundle:Admin:ban.html.twig', array(
      //'listAddresses' => $listAddresses,
      'form' => $form->createView(),
      ));
      } */

    /**
     * This action will allow a banned email address to post adverts and answers.
     * It's called by the button in the banned email addresses list.
     * @ParamConverter("bannedAddress", class="A2CPlatformBundle:BannedAddress")
     */
    public function unbanEmailAddressAction(Request $request, BannedAddress $bannedAddress)
    {
        $em = $this->getDoctrine()->getManager();

        // Empty form, just to handle CSRF token
        $form = $this->get('form.factory')->create();

        //Fetch the address from post request
        // @TODO : CSRF protection, at least throw a popup
        $em->remove($bannedAddress);
        $em->flush();

        $addressToUnban = $bannedAddress->getEmailAddress();

        $request->getSession()->getFlashBag()
                ->add('info', $this->get('translator')
                        ->trans('admin.ban.flashbag.unbanned', array('%address%' => $addressToUnban)));

        return $this->redirectToRoute('a2c_platform_admin_listbanned');
    }

    /**
     * This action will send an email to a subset of the whole website users.
     * The message is logged into the database.
     * It's called by the form on the send broadcast mail page.
     */
    public function sendBroadcastMailAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $broadcastMessage = new BroadcastMessage();

        // Create form
        $form = $this->get('form.factory')->create(SendBroadcastMailType::class, $broadcastMessage);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // @TODO : send to a subset of users
            // Fetch all the addresses mail from database
            $addressesList = $em->getRepository('A2CPlatformBundle:User')->findAllAdresses();

            // Call mail service
            $mailer = $this->container->get('mailer');
            $mail = (new \Swift_Message($broadcastMessage->getTitle()))
                    ->setFrom($this->getParameter('mailer_address'))
                    ->setTo($addressesList)
                    ->setBody($broadcastMessage->getMessage())
            ;
            $mailer->send($mail);

            // Log mail into the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($broadcastMessage);
            $em->flush();

            $request->getSession()->getFlashBag()
                    ->add('info', $this->get('translator')
                            ->trans('admin.broadcast.flashbag.mailsent', array('%nbAddresses%' => count($addressesList))));

            return $this->redirectToRoute('a2c_platform_admin');
        }

        return $this->render('A2CPlatformBundle:Admin:sendMail.html.twig', array(
                    'form' => $form->createView())
        );
    }

}
