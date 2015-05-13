<?php
/**
 * Ranking controller
 *
 * PHP version 5
 *
 * This file contains controller of players ranking.
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
use Model\RankingModel;

/**
 * Class RankingController
 *
 * This class contains definitions of game ranking.
 *
 * @category Controller
 * @package Controller
 * @author Aleksandra Wierzbiak
 * @uses Silex\ControllerProviderInterface
 * @uses Symfony\Component\HttpFoundation\Request
 */
class RankingController implements ControllerProviderInterface
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
        $rankingController = $app['controllers_factory'];
        $rankingController->get('/', array($this, 'viewranking'))->bind('/ranking');
        return $rankingController;
    }

    /**
     * View ranking
     *
     * This method allows to display ranking of all users.
     * 
     * @access public
     * @param application $app
     * @return mixed
     */
    public function viewranking(Application $app)
    {
    	$rankingModel = new RankingModel($app);
        $ranking = $rankingModel->viewRanking($app);
        return $app['twig']->render('ranking.twig', array('ranking' => $ranking));
    }

}