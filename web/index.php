<?php
/**
 * This is the main project file. It defines all controllers, service providers 
 * and runs the application.
 *
 */
 
/****** INITIALIZING SILEX APPLICATION ******/
require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();
$app['debug'] = true;

/****** REGISTERING SERVICEPROVIDERS ******/
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array('translator.domains' => array(),));

/****** REGISTERING TWIG ******/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/View',
));

/****** CONNECTING TO DATABASE ******/
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
		'db.options' => array(
   	 	'driver'    => 'pdo_mysql',
   	 	'host'      => 'localhost',
   	 	'dbname'    => 'bomberwoman',
    	'user'      => '',
   		'password'  => '',
    	'charset'   => 'utf8',
		),
));

/****** SECURITY SERVICE PROVIDER ******/
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
'security.firewalls' => array(
    'admin' => array(
        'pattern' => '^.*$',
        'form' => array(
            'login_path' => '/auth/login',
            'check_path' => '/login_check',
            'default_target_path'=> '/',
            'username_parameter' => 'form[username]',
            'password_parameter' => 'form[password]',
        ),
        'anonymous' => true,
        'logout' => array('logout_path' => '/auth/logout'),
        'users' => $app->share(function() use ($app) {
            return new User\UserProvider($app);
        }),
    ),
),
'security.access_rules' => array(
    array('^/auth.+$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
    array('^/register.+$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
    array('^/$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
    array('^/settings.+$', 'ROLE_USER'),
    array('^/game.+$', 'ROLE_USER'),
    array('^/users.+$', 'ROLE_ADMIN')
),
'security.role_hierarchy' => array(
    'ROLE_ADMIN' => array('ROLE_USER'),
),
));

/****** MOUNTING ALL CONTROLERS ******/
$app->mount('/', new Controller\InterfaceController());
$app->mount('/auth/', new Controller\AuthController());
$app->mount('/register/', new Controller\RegController());
$app->mount('/users/', new Controller\UsersController());
$app->mount('/game/', new Controller\GameController());
$app->mount('/statistics/', new Controller\StatisticsController());
$app->mount('/ranking/', new Controller\RankingController());

$app->run();