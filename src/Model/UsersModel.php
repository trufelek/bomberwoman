<?php

 /**
 * Users model
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
 * Class UsersModel
 *
 * @package Model
 * @author Aleksandra Wierzbiak <aleksandra.wierzbiak@uj.edu.pl>
 * @uses Doctrine\DBAL\DBALException
 * @uses Silex\Application
 * @uses Symfony\Component\Security\Core\Exception\UnsupportedUserException
 * @uses Symfony\Component\Security\Core\Exception\UsernameNotFoundException
 */

class UsersModel
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
     * @access public
     * @param application $app
     */
    public function __construct(Application $app)
    {
        $this->_app = $app;
        $this->_db = $app['db'];
    }

    /**
     * Load user by login
     *
     * Loading user details to session using given login.
     * 
     * @access public
     * @param $login
     * @return $user
     */
    public function loadUserByLogin($login)
    {
        $data = $this->getUserByLogin($login);

        if (!$data) {
            throw new UsernameNotFoundException(sprintf('Nick "%s" does not exist.', $login));
        }

        $roles = $this->getUserRoles($data['id']);

        if (!$roles) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $login));
        }

        $user = array(
            'login' => $data['login'],
            'password' => $data['password'],
            'roles' => $roles
        );

        return $user;
    }

    /**
     * Load user by id
     *
     * Connecting to database and getting user details using given id.
     * 
     * @access public
     * @param $id
     * @return mixed
     */
    public function loadUserById($id)
    {
        if (($id != " ") && ctype_digit((string)$id)) {
            $sql = 'SELECT id, login FROM users WHERE id= ?';
            return $this->_db->fetchAssoc($sql, array((int) $id));
        }
    }

    /**
     * Get user by login
     *
     * Connecting to database and getting user details using given login.
     * 
     * @access public
     * @param $login
     * @return mixed
     */
    public function getUserByLogin($login)
    {
        $sql = 'SELECT * FROM users WHERE login = ?';
        return $this->_db->fetchAssoc($sql, array((string) $login));
    }

    /**
     * Get users roles
     *
     * Loading user roles to session using given id.
     * 
     * @access public
     * @param $userId
     * @return $roles
     */
     public function getUserRoles($userId)
    {
        $sql = '
            SELECT
                roles.role
            FROM
                users_roles
            INNER JOIN
                roles
            ON users_roles.role_id=roles.id
            WHERE
                users_roles.user_id = ?
            ';

        $result = $this->_db->fetchAll($sql, array((string) $userId));

        $roles = array();
        foreach($result as $row) {
            $roles[] = $row['role'];
        }

        return $roles;
    }

    /**
     * Register user
     *
     * This method allows to register.
     * 
     * @access public
     * @param $data
     * @param $password
     * @return void
     */
    public function registerUser($data, $password)
    {
        $sql = 'INSERT INTO users (login, password) VALUES (?,?)';
        $this->_db->executeQuery($sql, array($data['login'], $password));

        $user_id = $this->getUserId($data['login']);
        $this->addUserRole($user_id['id']);

    }

    /**
     * Get user id
     *
     * Loading user id using given login.
     * 
     * @access public
     * @param $login
     * @return mixed
     */
     public function getUserId($login)
    {
        $sql = 'SELECT id FROM users WHERE login = ?';
        return $this->_db->fetchAssoc($sql, array((string) $login));
    }

    /**
     * Add user role
     *
     * Adding role to user using given id.
     * 
     * @access public
     * @param $user_id
     * @return void
     */
    public function addUserRole($user_id)
    {
        $sql = 'INSERT INTO users_roles (id, user_id, role_id) VALUES(?,?,?)';
        $this->_db->executeQuery($sql, array(NULL, (string) $user_id, 2)); 
    }

    /**
     * View all users
     *
     * This method allows to view all users from database.
     * 
     * @access public
     * @return mixed
     */
    public function viewAllUsers()
    {
        $sql = 'SELECT users.id, login, users_roles.role_id, users_roles.user_id FROM users JOIN users_roles ON users.id = users_roles.user_id;';
        return $this->_db->fetchAll($sql);
    }

    /**
     * Edit user
     *
     * This method allows to edit user login.
     * 
     * @access public
     * @param $data
     * @return void
     */
    public function editUser($data)
    {
        if (isset($data['id']) && ctype_digit((string)$data['id'])) {
                $sql = 'UPDATE users SET login = ? WHERE id = ?';
                $this->_db->executeQuery($sql, array($data['login'], $data['id']));

            
         } else {
                $sql = 'INSERT INTO users (login) VALUES (?)';
                $this->_db->executeQuery($sql, array($data['login']));
         }
    }

    /**
     * Get current user
     *
     * Get information about logged user.
     *
     * @access protected
     * @param application $app
     * @return $user
     */
    protected function getCurrentUser($app)
    {
        $user = null;
        $token = $app['security']->getToken();
        if (null !== $token) {
            $user = $token->getUser()->getUsername();
        }

        return $user;
    }

    /**
     * Get current user login
     *
     * This method retrieves current user login.
     * 
     * @access public
     * @param application $app
     * @return $login
     */
    public function getCurrentUserLogin($app)
    {
        $login = $this->getCurrentUser($app);
        return $login;
    }
}