<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $twig;
    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['function','cookie'];

    /**
     * Constructor.
     */
    public function __construct()
    {
        date_default_timezone_set('Europe/Istanbul');
        $user = new \App\Models\clients();
        $settings = new \App\Models\settings();
        $session = \Config\Services::session();

        $this->settings = $settings->where('id', '1')->get()->getResultArray()[0];
        if($session->get('neira_userlogin') && $session->get('neira_userlogin') == 1){

            $getuser = $user->where('client_id', $session->get('neira_userid'))->get()->getResultArray()[0];
            $getuser['access'] = json_decode($getuser['access'], true);

        }else{
            $getuser = 0;
        }
        $this->getuser = $getuser;
        $this->db = db_connect();
    }

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        ob_start(function($data) {
				$replace = [
					'/\>[^\S ]+/s'   => '>',
					'/[^\S ]+\</s'   => '<',
					'/([\t ])+/s'  => ' ',
					'/^([\t ])+/m' => '',
					'/([\t ])+$/m' => '',
					'~//[a-zA-Z0-9 ]+$~m' => '',
					'/[\r\n]+([\t ]?[\r\n]+)+/s'  => "\n",
					'/\>[\r\n\t ]+\</s'    => '><',
					'/}[\r\n\t ]+/s'  => '}',
					'/}[\r\n\t ]+,[\r\n\t ]+/s'  => '},',
					'/\)[\r\n\t ]?{[\r\n\t ]+/s'  => '){',
					'/,[\r\n\t ]?{[\r\n\t ]+/s'  => ',{',
					'/\),[\r\n\t ]+/s'  => '),',
				];
				$data = preg_replace(array_keys($replace), array_values($replace), $data);
				$remove = ['</option>', '</li>', '</dt>', '</dd>', '</tr>', '</th>', '</td>'];
				$data = str_ireplace($remove, '', $data);
				return $data;
			});
        // Preload any models, libraries, etc, here.
        $appPaths = new \Config\Paths();
        $appViewPaths = $appPaths->viewDirectory;

        $loader = new \Twig\Loader\FilesystemLoader($appViewPaths);

        $this->twig = new \Twig\Environment($loader, [
            //'cache' => WRITEPATH . '/cache/twig',
            "autoescape" => false
        ]);
        
        // E.g.: $this->session = \Config\Services::session();
    }
}
