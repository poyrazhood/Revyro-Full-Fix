<?php

namespace App\Controllers\main;

use CodeIgniter\Controller;

class Orders extends Controller
{
    function index(){
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
    }

}