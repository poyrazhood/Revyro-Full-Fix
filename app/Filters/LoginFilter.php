<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        $session->start();
        if (!$session->get('neira_userlogin')) {
            return 1;
        }else{
            return redirect()->to(base_url());
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}