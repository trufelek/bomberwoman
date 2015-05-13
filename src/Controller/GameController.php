<?php
/**
 * Game controller
 *
 * PHP version 5
 *
 * This file contains controller of displaying game.
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
 * Class GameController
 *
 * This class contains definitions of game.
 *
 * @category Controller
 * @package Controller
 * @author Aleksandra Wierzbiak
 * @uses Silex\ControllerProviderInterface
 * @uses Symfony\Component\HttpFoundation\Request
 */
class GameController implements ControllerProviderInterface
{
    /**
     * Connect
     *
     * This method provides routing.
     *
     * @access public
     * @param application $app
     * @return $gameController instance
     */
    public function connect(Application $app)
    {
        $gameController = $app['controllers_factory'];
        $gameController->get('/', array($this, 'game'))->bind('/game');
        return $gameController;
    }

    /**
     * Game
     *
     * This method allows to render the game.
     * 
     * @access public
     * @param application $app
     * @return mixed
     */
    public function game(Application $app)
    {
        return $app['twig']->render('index.twig');
    }
}