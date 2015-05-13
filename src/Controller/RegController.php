<?php
/**
 * Registration controller
 *
 * PHP version 5
 *
 * This file contains controller of user registration.
 *
 * @category  Controller
 * @package   Controller
 * @author    Aleksandra Wierzbiak
 * @version   1.0
 * @copyright Kraków, 09 September, 2014
 * @link      wierzba.wzks.uj.edu.pl/~12_wierzbiak
 */

namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Model\UsersModel;

/**
 * Class RegController
 *
 * This class contains definitions of registering users.
 *
 * @package Controller
 * @author Aleksandra Wierzbiak
 * @uses Silex\Application
 * @uses Silex\ControllerProviderInterface
 * @uses Symfony\Component\HttpFoundation\Request
 * @uses Symfony\Component\Validator\Constraints as Assert;
 * @uses Model\UsersModel;
 */
class RegController implements ControllerProviderInterface
{
    /**
     * Connect
     *
     * This method provides routing.
     *
     * @access public
     * @param application $app
     * @return $authController instance
     */
    public function connect(Application $app)
    {
        $regController = $app['controllers_factory'];
        $regController->match('/', array($this, 'register'))->bind('/register');
        return $regController;
    }

    /**
     * Register
     *
     * This method allows to register user.
     *
     * @access public
     * @param application $app
     * @param request $request
     * @return mixed
     */
    public function register(Application $app, Request $request)
    {
        $data = array();

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add('login', 'text', array(
                'invalid_message' => 'Nick musi mieć minimum 5 znaków.',
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
             ->add('password', 'repeated', array( 
                'type' => 'password', 
                'invalid_message' => 'Pola haseł muszą się zgadzać.', 
                'options' => array('attr' => array('class' => 'password-field')), 
                'required' => true,
                'first_options' => array('label' => 'Password'), 
                'second_options' => array('label' => 'Repeat password'), 
                'label' => 'Password', 'constraints' => array(new Assert\NotBlank(), new Assert\Length(array( 'min' => 6)))
            ))
            ->add('Register', 'submit')
            ->getForm();

        $form->handleRequest($request);
        $password = $form['password']->getData();
        $password = $app['security.encoder.digest']->encodePassword("{$password}", '');
        if ($form->isValid()) {
            $usersModel = new UsersModel($app);
            $user = $usersModel->registerUser($form->getData(), $password);
            return $app->redirect($app['url_generator']->generate('/auth/login'), 301);
            }
        return $app['twig']->render('reg.twig', array('form' => $form->createView()));
    }
}