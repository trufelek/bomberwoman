<?php
 /**
 * Ranking model
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
 * Class RankingModel
 *
 * @category Model
 * @package Model
 * @author Aleksandra Wierzbiak <aleksandra.wierzbiak@uj.edu.pl>
 * @uses Doctrine\DBAL\DBALException
 * @uses Silex\Application
 * @uses Symfony\Component\Security\Core\Exception\UnsupportedUserException
 * @uses Symfony\Component\Security\Core\Exception\UsernameNotFoundException
 */

class RankingModel
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
     * Constructor
     * 
     * @access public
     * @param application $app
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;
        $this->_db = $app['db'];
    }


    /**
     * View ranking
     *
     * This method allows to view ranking of all users.
     * 
     * @access public
     * @return mixed
     */
    public function viewRanking()
    {
        $sql = 'SELECT DISTINCT login, score from scores ORDER BY score DESC;';
        return $this->_db->fetchAll($sql);
    }
}