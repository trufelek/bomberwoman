<?php
/**
 * Authentication controller
 *
 * PHP version 5
 *
 * This file contains controller of user authorization.
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
 * Class AuthController
 *
 * This class contains definitions of user authorization methods.
 *
 * @category Controller
 * @package Controller
 * @author Aleksandra Wierzbiak
 * @uses Silex\Application
 * @uses Silex\ControllerProviderInterface
 * @uses Symfony\Component\HttpFoundation\Request
 * @uses Symfony\Component\Validator\Constraints
 * @uses Model\UsersModel
 */
class AuthController implements ControllerProviderInterface
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
        $authController = $app['controllers_factory'];
        $authController->match('/login', array($this, 'login'))->bind('/auth/login');
        $authController->match('/logout', array($this, 'logout'))->bind('/auth/logout');
        return $authController;
    }

    /**
     * Login
     *
     * This method defines login procedure.
     * 
     * @access public
     * @param application $app
     * @param request $request
     * @return mixed
     */
    public function login(Application $app, Request $request)
    {
        $data = array();

        $form = $app['form.factory']->createBuilder('form')
            ->add('username', 'text', array('label' => 'Nick', 'data' => $app['session']->get('_security.last_username')))
            ->add('password', 'password', array('label' => 'Hasło'))
            ->add('login', 'submit')
            ->getForm();

        return $app['twig']->render('login.twig', array(
            'form' => $form->createView(),
            'error' => $app['security.last_error']($request)
        ));
    }

    /**
     * Logout
     *
     * This method defines logout procedure.
     * 
     * @access public
     * @param application $app
     * @param request $request
     * @return mixed
     */
    public function logout(Application $app, Request $request)
    {
        $app['session']->clear();
        return $app['twig']->render('logout.twig');
    }

}