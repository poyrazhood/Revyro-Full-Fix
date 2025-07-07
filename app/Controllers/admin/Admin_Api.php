<?php

namespace App\Controllers\admin;

use CodeIgniter\Controller;
use http\Exception;
use PDO;

class Admin_Api extends Ana_Controller
{
    function index()
    {
        $this->response->setHeader('Content-Type', 'application/json');
        if ($this->request->getPost('key')) {
            $key = $this->request->getPost('key');
            $clients = new \App\Models\clients();
            $kontrol = $clients->where('apikey', $key);
            if ($kontrol->countAllResults()) {
                $user = $kontrol->where('apikey', $key)->get()->getResultArray()[0];
                $access = json_decode($user['access']);
                if (isset($access->admins) && $access->admins = 1) {
                    if ($this->request->getPost('action')) {
                        if ($this->request->getPost('action') == "getOrder") {
                            if ($this->request->getPost('type')) {
                                $type = $this->request->getPost('type');
                                $order = new \App\Models\orders();
                                $order_detail = $order->protect(false)->where('order_id', $type);
                                if ($order_detail->countAllResults()) {
                                    $order_d = $order->protect(false)->where('order_id', $type)->get()->getResultArray()[0];
                                    $result = json_encode(array(
                                        'status' => 'success',
                                        'id' => $order_d['order_id'],
                                        'user_id' => $order_d['client_id'],
                                        'link' => $order_d['order_url'],
                                        'quantity' => $order_d['order_quantity'],
                                        'charge' => $order_d['order_charge']
                                    ));

                                    return $this->response->setStatusCode(202)->setBody($result);
                                } else {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'no_order'
                                    ));
                                    return $this->response->setStatusCode(404)->setBody($result);
                                }
                            }
                        }
                        elseif ($this->request->getPost('action') == "getOrders") {


                            $order = new \App\Models\orders();
                            $order_detail = $order->protect(false);
                            if ($order_detail->countAllResults()) {
                                $order_d = $order->select("order_id as id,client_id as user_id,order_url as link,order_quantity as quantity,order_charge as charge")->protect(false)->get()->getResultArray();
                                $result = json_encode(array(
                                    'status' => 'success',
                                    'orders' => $order_d
                                ));

                                return $this->response->setStatusCode(202)->setBody($result);
                            } else {
                                $result = json_encode(array(
                                    'status' => 'fail',
                                    'error' => 'no_order'
                                ));
                                return $this->response->setStatusCode(404)->setBody($result);
                            }

                        }
                        elseif ($this->request->getPost('action') == "updateOrders") {
                            if ($this->request->getPost('orders')) {
                                $types = json_decode($this->request->getPost('orders'), true);

                                if (!is_array($types)) {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'no_orders_is_array'
                                    ));
                                    return $this->response->setStatusCode(404)->setBody($result);
                                }
                                $order = new \App\Models\orders();
                                $return = array();
                                foreach ($types as $type) {
                                    $where = $type;
                                    $id = $type['id'];
                                    unset($where['id']);
                                    $order_detail = $order->protect(false)->where('order_id', $type)->countAllResults();
                                    if ($order_detail) {
                                        if ($order->protect(false)->where('order_id', $id)->set($where)->update()) {
                                            $return[$id] = array('status' => "success");
                                        } else {
                                            $return[$id] = array('status' => "fail", 'error' => 'bad_request');
                                        }
                                    } else {
                                        $return[$id] = array('status' => 'fail', 'error' => 'no_orders');
                                    }

                                }
                                $result = json_encode(array(
                                    'status' => 'success',
                                    'orders' => $return
                                ));

                                return $this->response->setStatusCode(202)->setBody($result);

                            }
                        }
                        elseif ($this->request->getPost('action') == "setInprogress") {
                            if ($this->request->getPost('id')) {
                                $type = $this->request->getPost('id');
                                $order = new \App\Models\orders();
                                $order_detail = $order->protect(false)->where('order_id', $type);
                                if ($order_detail->countAllResults()) {
                                    $set = array();
                                    if ($this->request->getPost('start_count')) {
                                        $set['order_start'] = $this->request->getPost('start_count');
                                    }
                                    $set['order_status'] = 'inprogress';
                                    $order_d = $order->protect(false)->where('order_id', $type)->set($set)->update();
                                    $result = json_encode(array(
                                        'status' => 'success',
                                    ));

                                    return $this->response->setStatusCode(200)->setBody($result);
                                } else {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'bad_id'
                                    ));
                                    return $this->response->setStatusCode(404)->setBody($result);
                                }
                            } else {
                                $result = json_encode(array(
                                    'status' => 'fail',
                                    'error' => 'empty_id'
                                ));
                                return $this->response->setStatusCode(400)->setBody($result);
                            }
                        }
                        elseif ($this->request->getPost('action') == "setProcessing") {
                            if ($this->request->getPost('id')) {
                                $type = $this->request->getPost('id');
                                $order = new \App\Models\orders();
                                $order_detail = $order->protect(false)->where('order_id', $type);
                                if ($order_detail->countAllResults()) {
                                    $set = array();
                                    $set['order_status'] = 'processing';
                                    $order_d = $order->protect(false)->where('order_id', $type)->set($set)->update();
                                    $result = json_encode(array(
                                        'status' => 'success',
                                    ));

                                    return $this->response->setStatusCode(200)->setBody($result);
                                } else {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'bad_id'
                                    ));
                                    return $this->response->setStatusCode(404)->setBody($result);
                                }
                            } else {
                                $result = json_encode(array(
                                    'status' => 'fail',
                                    'error' => 'empty_id'
                                ));
                                return $this->response->setStatusCode(400)->setBody($result);
                            }
                        }
                        elseif ($this->request->getPost('action') == "setStartcount") {
                            if ($this->request->getPost('id')) {
                                $type = $this->request->getPost('id');
                                $order = new \App\Models\orders();
                                $order_detail = $order->protect(false)->where('order_id', $type);
                                if ($order_detail->countAllResults()) {
                                    $set = array();
                                    if ($this->request->getPost('start_count')) {
                                        $set['order_start'] = $this->request->getPost('start_count');
                                    }
                                    $set['order_status'] = 'inprogress';
                                    $order_d = $order->protect(false)->where('order_id', $type)->set($set)->update();
                                    $result = json_encode(array(
                                        'status' => 'success',
                                    ));

                                    return $this->response->setStatusCode(200)->setBody($result);
                                } else {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'bad_id'
                                    ));
                                    return $this->response->setStatusCode(404)->setBody($result);
                                }
                            } else {
                                $result = json_encode(array(
                                    'status' => 'fail',
                                    'error' => 'empty_id'
                                ));
                                return $this->response->setStatusCode(400)->setBody($result);
                            }
                        }
                        elseif ($this->request->getPost('action') == "setCanceled") {
                            if ($this->request->getPost('id')) {
                                $type = $this->request->getPost('id');
                                $order = new \App\Models\orders();
                                $order_detail = $order->protect(false)->where('order_id', $type);
                                if ($order_detail->countAllResults()) {
                                    $order_detail = $order->protect(false)->where('order_id', $type)->get()->getResultArray()[0];
                                    $user_id = $order_detail['client_id'];
                                    $balance = $order_detail['order_charge'];
                                    $user = new \App\Models\clients();
                                    $user->protect(false)->where('client_id', $user_id)->set('balance', 'balance+' . $balance, false)->update();

                                    $set = array();
                                    $set['order_status'] = 'canceled';

                                    $order_d = $order->protect(false)->where('order_id', $type)->set($set)->update();
                                    $result = json_encode(array(
                                        'status' => 'success',
                                    ));

                                    return $this->response->setStatusCode(200)->setBody($result);
                                } else {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'bad_id'
                                    ));
                                    return $this->response->setStatusCode(404)->setBody($result);
                                }
                            } else {
                                $result = json_encode(array(
                                    'status' => 'fail',
                                    'error' => 'empty_id'
                                ));
                                return $this->response->setStatusCode(400)->setBody($result);
                            }
                        }
                        elseif ($this->request->getPost('action') == "setPartial") {
                            if ($this->request->getPost('id') && $this->request->getPost('remains')) {
                                $type = $this->request->getPost('id');
                                $order = new \App\Models\orders();
                                $order_detail = $order->protect(false)->where('order_id', $type);
                                if ($order_detail->countAllResults()) {
                                    $order_detail = $order->protect(false)->where('order_id', $type)->get()->getResultArray()[0];
                                    $user_id = $order_detail['client_id'];
                                    $user = new \App\Models\clients();
                                    $user->protect(false)->where('client_id', $user_id)->set('balance', 'balance+' . $this->request->getPost('remains'), false)->update();

                                    $set = array();
                                    $set['order_status'] = 'partial';
                                    $order_d = $order->protect(false)->where('order_id', $type)->set($set)->update();
                                    $result = json_encode(array(
                                        'status' => 'success',
                                    ));

                                    return $this->response->setStatusCode(200)->setBody($result);
                                } else {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'bad_id'
                                    ));
                                    return $this->response->setStatusCode(404)->setBody($result);
                                }
                            } else {
                                $result = json_encode(array(
                                    'status' => 'fail',
                                    'error' => 'empty_id_or_empty_remains'
                                ));
                                return $this->response->setStatusCode(400)->setBody($result);
                            }
                        }
                        elseif ($this->request->getPost('action') == "setCompleted") {
                            if ($this->request->getPost('id')) {
                                $type = $this->request->getPost('id');
                                $order = new \App\Models\orders();
                                $order_detail = $order->protect(false)->where('order_id', $type);
                                if ($order_detail->countAllResults()) {
                                    $set = array();
                                    $set['order_status'] = 'completed';
                                    $order_d = $order->protect(false)->where('order_id', $type)->set($set)->update();
                                    $result = json_encode(array(
                                        'status' => 'success',
                                    ));

                                    return $this->response->setStatusCode(200)->setBody($result);
                                } else {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'bad_id'
                                    ));
                                    return $this->response->setStatusCode(404)->setBody($result);
                                }
                            } else {
                                $result = json_encode(array(
                                    'status' => 'fail',
                                    'error' => 'empty_id'
                                ));
                                return $this->response->setStatusCode(400)->setBody($result);
                            }
                        }
                        elseif ($this->request->getPost('action') == "setRemains") {
                            if ($this->request->getPost('id') && $this->request->getPost('remains')) {
                                $type = $this->request->getPost('id');
                                $order = new \App\Models\orders();
                                $order_detail = $order->protect(false)->where('order_id', $type);
                                if ($order_detail->countAllResults()) {
                                    $set = array();

                                    $set['order_remains'] = $this->request->getPost('remains');

                                    $order_d = $order->protect(false)->where('order_id', $type)->set($set)->update();
                                    $result = json_encode(array(
                                        'status' => 'success',
                                    ));

                                    return $this->response->setStatusCode(200)->setBody($result);
                                } else {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'bad_id'
                                    ));
                                    return $this->response->setStatusCode(404)->setBody($result);
                                }
                            } else {
                                $result = json_encode(array(
                                    'status' => 'fail',
                                    'error' => 'empty_id_or_remains'
                                ));
                                return $this->response->setStatusCode(400)->setBody($result);
                            }
                        }
                        elseif ($this->request->getPost('action') == "addPayment") {
                            if ($this->request->getPost('id') || $this->request->getPost('username')) {
                                if ($this->request->getPost('amount') && $this->request->getPost('details')) {
                                    $type = $this->request->getPost('id') ? $this->request->getPost('id') : $this->request->getPost('username');
                                    $clients = new \App\Models\clients();
                                    $where_user = array();
                                    if ($this->request->getPost('id')) {
                                        $where_user['client_id'] = $type;
                                    } else {
                                        $where_user['username'] = $type;

                                    }
                                    $user_detail = $clients->protect(false)->where($where_user);
                                    if ($user_detail->countAllResults()) {

                                        $user_detail = $clients->protect(false)->where($where_user)->set('balance', 'balance +' . $this->request->getPost('amount'), false)->update();

                                        $result = json_encode(array(
                                            'status' => 'success',
                                        ));

                                        return $this->response->setStatusCode(201)->setBody($result);
                                    } else {
                                        $result = json_encode(array(
                                            'status' => 'fail',
                                            'error' => 'bad_username_or_id'
                                        ));
                                        return $this->response->setStatusCode(404)->setBody($result);
                                    }
                                } else {
                                    $result = json_encode(array(
                                        'status' => 'fail',
                                        'error' => 'empty_amount_or_details'
                                    ));
                                    return $this->response->setStatusCode(400)->setBody($result);
                                }
                            } else {
                                $result = json_encode(array(
                                    'status' => 'fail',
                                    'error' => 'empty_username_or_id'
                                ));
                                return $this->response->setStatusCode(400)->setBody($result);
                            }
                        }
                        else {
                            $result = json_encode(array(
                                'status' => 'fail',
                                'error' => 'empty_type'
                            ));
                            return $this->response->setStatusCode(400)->setBody($result);

                        }

                    } else {
                        $result = json_encode(array(
                            'status' => 'fail',
                            'error' => 'empty_action'
                        ));
                        return $this->response->setStatusCode(400)->setBody($result);

                    }


                } else {
                    $result = json_encode(array(
                        'status' => 'fail',
                        'error' => 'bad_auth'
                    ));
                    return $this->response->setStatusCode(401)->setBody($result);
                }
            } else {
                $result = json_encode(array(
                    'status' => 'fail',
                    'error' => 'bad_auth'
                ));
                return $this->response->setStatusCode(401)->setBody($result);

            }
        } else {
            $result = json_encode(array(
                'status' => 'fail',
                'error' => 'bad_request'
            ));

            return $this->response->setStatusCode(400)->setBody($result);
        }
    }
}