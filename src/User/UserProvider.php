<?php
/**
 * User Provider
 *
 * This file contains user provider.
 *
 * @category  Provider
 * @package   Provider
 * @author    Aleksandra Wierzbiak
 * @version   1.0
 * @copyright Kraków, 09 September, 2014
 * @link      wierzba.wzks.uj.edu.pl/~12_wierzbiak
 */

namespace User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Model\UsersModel;

/**
 * Class UserProvider
 *
 * This class contains definitions of user provider.
 *
 * @category  Provider
 * @package Provider
 * @author Aleksandra Wierzbiak
 * @uses Symfony\Component\Security\Core\User\UserProviderInterface
 * @uses Symfony\Component\Security\Core\User\UserInterface
 * @uses Symfony\Component\Security\Core\User\User
 * @uses Symfony\Component\Security\Core\Exception\UsernameNotFoundException
 * @uses Symfony\Component\Security\Core\Exception\UnsupportedUserException
 */
class UserProvider implements UserProviderInterface
{
    /**
     * Application object.
     *
     * @access protected
     * @var $_app
     */
    protected $_app;

    public function __construct($app)
    {
        $this->_app = $app;
    }

    /**
     * Load user by username
     *
     * Loading user details to session using given login.
     * 
     * @param $login
     * @return mixed
     */
    public function loadUserByUsername($login)
    {
        $userModel = new UsersModel($this->_app);
        $user = $userModel->loadUserByLogin($login);
        return new User($user['login'], $user['password'], $user['roles'], true, true, true, true);
    }

     /**
     * Refresh user
     *
     * Refreshing user details to session using given user.
     * 
     * @param $user
     * @return mixed
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

     /**
     * Supporting class
     * 
     * @param $class
     * @return $class
     */
    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}
