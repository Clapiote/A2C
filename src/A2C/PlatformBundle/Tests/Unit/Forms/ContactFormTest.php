<?php

// src/A2C/PlatformBundle/Test/Unit/Controllers/ContactFormTest.php

namespace A2C\PlatformBundle\Tests\Unit\Controllers;

use A2C\PlatformBundle\Entity\ContactMessage;
use A2C\PlatformBundle\Form\ContactMessageType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

/**
 * Description of ContactFormTest
 *
 * @author Vincent
 */
final class ContactFormTest extends TypeTestCase
{

    public function setUp() 
    {
        parent::setUp();
    }

    public function testContactFormConstruction() 
    {
        $formData = array(
            'name' => 'test',
            'emailAddress' => 'test@toto.com',
            'message' => 'msg test'
        );

        $form = $this->factory->create(ContactMessageType::class);
        $cm = ContactMessage::fromArray($formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($cm, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * Test the success of sending a message via the form on the contact page.
     * @TODO : this tests won't work with a captcha
     */
    /* public function testSendContactMessage() 
      {
      return true;
      } */

    /**
     * Test the fail of sendind a message with a non valid e-mail address
     */
    public function testBadEmailAddress() 
    {
        $formData = array(
            'name' => 'test',
            'emailAddress' => 'testtoto.com',
            'message' => 'msg test'
        );

        $cm = ContactMessage::fromArray($formData);
        $validator = Validation::createValidatorBuilder()
                ->enableAnnotationMapping()->getValidator();
        $errors = $validator->validate($cm);
        $this->assertNotEquals(0, count($errors));
    }

    /**
     * Test the fail of sending a message with forbidden words
     * @see TextFilter service
     */
    /* public function testSendSpamMessage()
      {
      return true;
      } */
}
