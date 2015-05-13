<?php

/**
 * Interface controller
 *
 * PHP version 5
 *
 * This file contains controller of user interface.
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

/**
 * Class InterfaceController
 *
 * This class contains definitions of displaying user interface.
 *
 * @category Controller
 * @package Controller
 * @uses Silex\Application
 * @uses Silex\ControllerProviderInterface
 * @uses Symfony\Component\HttpFoundation\Request
 */
class InterfaceController implements ControllerProviderInterface
{
    /**
     * Connect
     *
     * This method provides routing.
     *
     * @access public
     * @param application $app
     * @return $interfaceController instance
     */
    public function connect(Application $app)
    {
        $interfaceController = $app['controllers_factory'];
        $interfaceController->get('/', array($this, 'interfaceLoader'))->bind('/');
        return $interfaceController;
    }

    /**
     * Interface loader
     *
     * Loading interface twig.
     *
     * @access public
     * @param application $app
     * @return mixed
     */
    public function interfaceLoader(Application $app)
    {
        return $app['twig']->render('interface.twig');
    }
}