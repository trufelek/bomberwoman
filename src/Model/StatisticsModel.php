<?php

 /**
 * Statistics model
 *
 * PHP version 5
 *
 * @category Model
 * @package  Model
 * @author   Aleksandra Wierzbiak <aleksandra.wierzbiak@uj.edu.pl>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version  1.0
 * @link     wierzba.wzks.uj.edu.pl/~12_wierzbiak
 */

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class StatisticsModel
 *
 * @package Model
 * @author Aleksandra Wierzbiak <aleksandra.wierzbiak@uj.edu.pl>
 * @uses Doctrine\DBAL\DBALException
 * @uses Silex\Application
 * @uses Symfony\Component\Security\Core\Exception\UnsupportedUserException
 * @uses Symfony\Component\Security\Core\Exception\UsernameNotFoundException
 */

class StatisticsModel
{
    /**
     * Application object
     *
     * @var _app contains application.
     * @access protected
     */
    protected $_app;

    /**
     * Database object
     *
     * @var _db contains database.
     * @access protected
     */
    protected $_db;

    /**
     * Constructor.
     * 
     * @param application 
     * @access public
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;
        $this->_db = $app['db'];
    }

     /**
     * Save score
     *
     * This method allows to save user's score.
     * 
     * @access public
     * @param $login
     * @param $score
     * @return void
     */
    public function saveScore($login, $score)
    {
    	$sql = 'INSERT INTO scores (login, score) VALUES (?,?)';
        $this->_db->executeQuery($sql, array($login, $score));
    }

}