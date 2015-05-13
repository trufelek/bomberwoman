<?php
/**
 * Users controller
 *
 * PHP version 5
 *
 * This file contains controller of managing users.
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
 * Class UsersController
 * 
 * This class contains definitions of user management methods.
 *
 * @category  Controller
 * @package Controller
 * @author Aleksandra Wierzbiak
 * @uses Silex\Application
 * @uses Silex\ControllerProviderInterface
 * @uses Symfony\Component\HttpFoundation\Request
 * @uses Symfony\Component\Validator\Constraints
 * @uses Model\UsersModel
 */
class UsersController implements ControllerProviderInterface
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
        $usersController = $app['controllers_factory'];
        $usersController->match('/viewusers', array($this, 'viewUsers'))->bind('/users/viewusers');
        $usersController->match('/edit/{id}', array($this, 'editUsers'))->bind('/users/edit');
        $usersController->match('/add', array($this, 'addUsers'))->bind('/users/add');
        $usersController->match('/delete', array($this, 'deleteUsers'))->bind('/users/delete');
        return $usersController;
    }

    /**
     * View users
     *
     * This method allows to view all users.
     * 
     * @access public
     * @param application $app
     * @param request $request
     * @return mixed
     */
    public function viewUsers(Application $app, Request $request)
    {
    	$usersModel = new UsersModel($app);
    	$users = $usersModel->viewAllUsers();
    	return $app['twig']->render('users.twig', array('users' => $users));
    }

    /**
     * Edit users
     *
     * This method allows to edit users.
     * 
     * @access public
     * @param application $app
     * @param request $request
     * @return mixed
     */
    public function editUsers(Application $app, Request $request){
		$usersModel = new UsersModel($app);
		$id = (int) $request->get('id', 0);
		$user = $usersModel->loadUserById($id);
		 if (count($user)){
			$form = $app['form.factory']->createBuilder('form', $user)
				->add('login', 'text', array(
                	'invalid_message' => 'Nick musi mieć minimum 5 znaków.',
                	'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            	))
				->add('Zapisz zmiany', 'submit')
				->getForm();

			$form->handleRequest($request);
			if ($form->isValid()) {
				$usersModel = new UsersModel($app);
				$user = $usersModel->editUser($form->getData());
				return $app->redirect($app['url_generator']->generate('/users/viewusers'), 301);
			}
			return $app['twig']->render('edit.twig', array('form' => $form->createView(), 'user' => $user));

		} else {
			return 'Wystąpił błąd podczas edycji danych.';
		}
	}
}
