<?php
/**
 * Statistics controller
 *
 * PHP version 5
 *
 * This file contains controller of players statistics.
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
use Silex\Provider\SessionServiceProvider;
use Model\UsersModel;
use Model\StatisticsModel;

/**
 * Class StatisticsController
 *
 * This class contains definitions of game statistics.
 *
 * @category  Controller
 * @package Controller
 * @author Aleksandra Wierzbiak
 * @uses Silex\ControllerProviderInterface
 * @uses Symfony\Component\HttpFoundation\Request
 * @uses Silex\Provider\SessionServiceProvider
 */
class StatisticsController implements ControllerProviderInterface
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
        $statisticsController = $app['controllers_factory'];
        $statisticsController->get('/', array($this, 'viewscore'))->bind('/statistics');
        return $statisticsController;
    }

    /**
     * View score
     *
     * This method allows to show user the score and save it in database.
     * 
     * @access public
     * @param application $app
     * @return mixed
     */
    public function viewscore(Application $app)
    {
    	$score = $_GET['score'];
    	$usersModel = new UsersModel($app);
        $login = $usersModel->getCurrentUserLogin($app);

        $statisticsModel = new StatisticsModel($app);
        $saved_score = $statisticsModel->saveScore($login, $score);
        return $app['twig']->render('statistics.twig', array('score' => $score));
    }
}