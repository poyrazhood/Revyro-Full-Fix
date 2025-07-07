<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-param" content="_csrf_admin">
    <meta name="csrf-token" content="o4-_bHdgSFgqiNObPfcV_oC6wg_uT3awHru3i8EpQQTrztkcITQQHm3ft-FOrSG17-KJW6EWOYdb8-jGtBB2aw==">
    <title>Admin API</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <link href="<?=base_url('assets/css/admin/')?>/main3d00.css?v=1635516356" rel="stylesheet">  </head>
    <body class="">
        <script src="../browser.sentry-cdn.com/6.13.2/bundle.min.js" integrity="sha384-fcgCrdIqrZ6d6fA8EfCAfdjgN9wXDp0EOkueSo3bKyI3WM4tQCE0pOA/kJoqHYoI" crossorigin="anonymous"></script>
        <script src="js/admin-sentry-init3d00.js?v=1635516356"></script>
        <noscript>You need to enable JavaScript to run this app.</noscript>

        <nav class="navbar navbar-fixed-top navbar-default">
          <div class="container-fluid">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" data-nav="navbar-priority" id="bs-navbar-collapse">
        </div>
    </div>
</nav>


<!-- Themes twig docs START -->
<div class="container">
    <div class="row">

        <div class="col-md-2 col-md-offset-1">

            <ul class="nav nav-pills nav-stacked nav-pills-stacked-example" id="documentation-nav">
                <li id="list-method-getorder"><a href="#method-getorder">getOrder</a></li>
                <li id="list-method-getorders"><a href="#method-getorders">getOrders</a></li>
                <li id="list-method-updateorders"><a href="#method-updateorders">updateOrders</a></li>
                <li id="list-method-setinprogress"><a href="#method-setinprogress">setInprogress</a></li>
                <li id="list-method-setprocessing"><a href="#method-setprocessing">setProcessing</a></li>
                <li id="list-method-setstartcount"><a href="#method-setstartcount">setStartcount</a></li>
                <li id="list-method-setcanceled"><a href="#method-setcanceled">setCanceled</a></li>
                <li id="list-method-setpartial"><a href="#method-setpartial">setPartial</a></li>
                <li id="list-method-setcompleted"><a href="#method-setcompleted">setCompleted</a></li>
                <li id="list-method-setremains"><a href="#method-setremains">setRemains</a></li>
                <li id="list-method-addpayment"><a href="#method-addpayment">addPayment</a></li>
            </ul>

        </div>
        <div class="col-md-9">
            <div class="panel">
                <div class="panel-body documentation-body">


                    <div class="documentation-title" id="anotomyoftheme">Admin API</div>
                    <br>
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="whiten">
                                <td class="documentation-th"><b>Authentication</b></td>
                                <td>
                                    Required
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="documentation-th"><b>HTTP Method</b></td>
                                <td>
                                    POST
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="documentation-th"><b>API Url</b></td>
                                <td>
                                    https://glycondemo.com/adminapi/v1
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="documentation-th"><b>Response</b></td>
                                <td>
                                    JSON
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <h3 id="method-getorder" class="method-title">Method getOrder</h3>
                    <p>Gives one Pending order. After success request order status for that order will be changed to
                    Processing.</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tbody>
                                <tr class="grayen">
                                    <th class="admin-api-title documentation-th">Parameters</th>
                                    <th class="documentation-th">Description</th>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>key</b></td>
                                    <td>Your Admin API key</td>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>action</b></td>
                                    <td>Method name</td>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>type</b></td>
                                    <td>Service id</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tbody>
                                <tr class="grayen">
                                    <th class="admin-api-title documentation-th">Response</th>
                                    <th class="documentation-th">Value</th>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>status</b></td>
                                    <td>Whether or not the request succeeded</td>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>id</b></td>
                                    <td>Order id</td>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>link</b></td>
                                    <td>Order link</td>
                                </tr>
                                <tr class="whiten">
                                    <td><b>quantity</b></td>
                                    <td>Order quantity</td>
                                </tr>
                                <tr class="whiten">
                                    <td><b>charge</b></td>
                                    <td>Order charge</td>
                                </tr>
                                <tr class="whiten">
                                    <td><b>user_id</b></td>
                                    <td>Order user id</td>
                                </tr>
                                <tr class="whiten">
                                    <td><b>error</b></td>
                                    <td>
                                        <b>bad_type</b> - incorrect service type<br>
                                        <b>bad_auth</b> - incorrect API key<br>
                                        <b>no_order</b> - there is not pending orders<br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="documentation-example">
                        <pre>
                            {
                             "status":"success",
                             "id":"123922",
                             "link":"https://facebook.com/test",
                             "quantity":"5000"
                         }</pre>
                         <pre>
                            {
                             "status":"fail",
                             "error":"bad_auth"
                         }</pre>
                     </div>

                     <h3 id="method-getorders" class="method-title">Method getOrders</h3>
                     <p>Gives 100 Pending orders. After success request order status for that order will be changed to
                     Processing.</p>
                     <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tbody>
                                <tr class="grayen">
                                    <th class="admin-api-title documentation-th">Parameters</th>
                                    <th class="documentation-th">Description</th>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>key</b></td>
                                    <td>Your Admin API key</td>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>action</b></td>
                                    <td>Method name</td>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>type</b></td>
                                    <td>Service id</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br/>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tbody>
                                <tr class="grayen">
                                    <th class="admin-api-title documentation-th">Response</th>
                                    <th class="documentation-th">Value</th>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>status</b></td>
                                    <td>Whether or not the request succeeded</td>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>orders</b></td>
                                    <td>Array of orders</td>
                                </tr>
                                <tr class="whiten">
                                    <td><b>error</b></td>
                                    <td>
                                        <b>bad_type</b> - incorrect service type<br>
                                        <b>bad_auth</b> - incorrect API key<br>
                                        <b>no_order</b> - there is not pending orders<br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="documentation-example">
                        <pre>
                            {
                                "status": :success",
                                "orders": [
                                {
                                    "id"; 1,
                                    "link"; "https://facebook.com/test",
                                    "charge"; 1,
                                    "user_id"; 111,
                                    "quantity": 100,
                                    "comments": ''
                                },
                                {
                                    "id": 2,
                                    "link": "https://facebook.com/test2",
                                    "charge": 2,
                                    "user_id": 112,
                                    "quantity": 200,
                                    "comments": ''
                                }
                            }</pre>
                            <pre>
                                {
                                 "status":"fail",
                                 "error":"bad_auth"
                             }</pre>
                         </div>

                         <h3 id="method-updateorders" class="method-title">Method updateOrders</h3>
                         <p>Update multiple orders</p>
                         <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <tbody>
                                    <tr class="grayen">
                                        <th class="admin-api-title documentation-th">Parameters</th>
                                        <th class="documentation-th">Description</th>
                                    </tr>
                                    <tr class="whiten">
                                        <td class="admin-api-title"><b>key</b></td>
                                        <td>Your Admin API key</td>
                                    </tr>
                                    <tr class="whiten">
                                        <td class="admin-api-title"><b>action</b></td>
                                        <td>Method name</td>
                                    </tr>
                                    <tr class="whiten">
                                        <td class="admin-api-title"><b>orders</b></td>
                                        <td>Array updated orders <strong>items</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <p>Updated order <strong>item</strong></p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <tbody>
                                    <tr class="grayen">
                                        <th class="admin-api-title documentation-th">Parameters</th>
                                        <th class="documentation-th">Description</th>
                                    </tr>
                                    <tr class="whiten">
                                        <td class="admin-api-title"><b>id</b></td>
                                        <td>Updated order ID</td>
                                    </tr>
                                    <tr class="whiten">
                                        <td class="admin-api-title"><b>status</b></td>
                                        <td>
                                            Order status (optional)
                                            <ul>
                                                <li><strong>in progress</strong></li>
                                                <li><strong>processing</strong></li>
                                                <li><strong>canceled</strong></li>
                                                <li><strong>partial</strong></li>
                                                <li><strong>completed</strong></li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr class="whiten">
                                        <td class="admin-api-title"><b>start_count</b></td>
                                        <td>Order start count (optional)</td>
                                    </tr>
                                    <tr class="whiten">
                                        <td class="admin-api-title"><b>remains</b></td>
                                        <td>Order remains (optional)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="documentation-example">
                            <p>Updated orders request body example</p>
                            <pre>{
                              "key": "your-api-key",
                              "action": "updateOrders",
                              "orders": [
                              {
                                  "id": "1",
                                  "status": "in progress"
                              },
                              {
                                  "id": "2",
                                  "status": "in progress",
                                  "start_count": "22"
                              },
                              {
                                  "id": "3",
                                  "status": "processing"
                              },
                              {
                                  "id": "4",
                                  "status": "canceled"
                              },
                              {
                                  "id": "5",
                                  "status": "partial",
                                  "remains": "33"
                              },
                              {
                                  "id": "6",
                                  "status": "completed"
                              },
                              {
                                  "id": "7",
                                  "start_count": "44"
                              },
                              {
                                  "id": "8",
                                  "remains": "55"
                              }
                              ]
                          }</pre>
                      </div>
                      <br>
                      <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tbody>
                                <tr class="grayen">
                                    <th class="admin-api-title documentation-th"><b>Response</b></th>
                                    <th class="documentation-th">Value</th>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>status</b></td>
                                    <td>
                                        Whether or not the request succeeded
                                    </td>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>orders</b></td>
                                    <td>
                                        Array of updated orders result
                                    </td>
                                </tr>
                                <tr class="whiten">
                                    <td class="admin-api-title"><b>error</b></td>
                                    <td>
                                        <b>bad_order_list</b> - incorrect updated orders array<br>
                                        <b>internal_error</b> - internal error<br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="documentation-example">
                        <pre>
                            {
                              "status": "success",
                              "orders": {
                                "1": {
                                  "status": "success"
                              },
                              "2": {
                                  "status": "success"
                              },
                              "3": {
                                  "status": "success"
                              },
                              "4": {
                                  "status": "fail",
                                  "error": "bad_id"
                              },
                              "5": {
                                  "status": "success"
                              },
                              "6": {
                                  "status": "fail",
                                  "error": "no_orders"
                              },
                              "7": {
                                  "status": "success"
                              },
                              "8": {
                                  "status": "success"
                              }
                          }
                      }</pre>
                      <pre>
                        {
                         "status":"fail",
                         "error":"bad_order_list"
                     }</pre>
                 </div>


                 <h3 id="method-setinprogress" class="method-title">Method setInprogress</h3>
                 <p>Setting start count for an order. After success request order status for that order will be
                 changed to In Progress.</p>
                 <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th">Parameters</th>
                                <th class="documentation-th">Description</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>key</b></td>
                                <td>Your Admin API key</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>action</b></td>
                                <td>Method name</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>id</b></td>
                                <td>Order id</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>start_count</b> (optional)</td>
                                <td>Start count</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th"><b>Response</b></th>
                                <th class="documentation-th">Value</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>status</b></td>
                                <td>
                                    Whether or not the request succeeded
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>error</b></td>
                                <td>
                                    <b>bad_id</b> - incorrect order id<br>
                                    <b>bad_auth</b> - incorrect API key<br>
                                    <b>bad_start_count</b> - incorrect Start count
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="documentation-example">
                    <pre>
                        {
                         "status":"success"
                     }</pre>
                     <pre>
                        {
                         "status":"fail",
                         "error":"bad_id"
                     }</pre>
                 </div>

                 <h3 id="method-setprocessing" class="method-title">Method setProcessing</h3>
                 <p>After success request order status for that order will be changed to Processing.</p>
                 <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th">Parameters</th>
                                <th class="documentation-th">Description</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>key</b></td>
                                <td>Your Admin API key</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>action</b></td>
                                <td>Method name</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>id</b></td>
                                <td>Order id</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th"><b>Response</b></th>
                                <th class="documentation-th">Value</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>status</b></td>
                                <td>
                                    Whether or not the request succeeded
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>error</b></td>
                                <td>
                                    <b>bad_id</b> - incorrect order id<br>
                                    <b>bad_auth</b> - incorrect API key<br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="documentation-example">
                    <pre>
                        {
                         "status":"success"
                     }</pre>
                     <pre>
                        {
                         "status":"fail",
                         "error":"bad_id"
                     }</pre>
                 </div>


                 <h3 id="method-setstartcount" class="method-title">Method setStartcount</h3>
                 <p>Setting start count for an order. After success request order status for that order will be
                 changed to In Progress.</p>
                 <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th">Parameters</th>
                                <th class="documentation-th">Description</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>key</b></td>
                                <td>Your Admin API key</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>action</b></td>
                                <td>Method name</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>id</b></td>
                                <td>Order id</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>start_count</b></td>
                                <td>Start count</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th"><b>Response</b></th>
                                <th class="documentation-th">Value</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>status</b></td>
                                <td>
                                    Whether or not the request succeeded
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>error</b></td>
                                <td>
                                    <b>bad_id</b> - incorrect order id<br>
                                    <b>bad_auth</b> - incorrect API key<br>
                                    <b>bad_start_count</b> - incorrect Start count
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="documentation-example">
                    <pre>
                        {
                         "status":"success"
                     }</pre>
                     <pre>
                        {
                         "status":"fail",
                         "error":"bad_id"
                     }</pre>
                 </div>

                 <h3 id="method-setcanceled" class="method-title">Method setCanceled</h3>
                 <p>Cancel and refund an order.</p>
                 <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th">Parameters</th>
                                <th class="documentation-th">Description</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>key</b></td>
                                <td>Your Admin API key</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>action</b></td>
                                <td>Method name</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>id</b></td>
                                <td>Order id</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th"><b>Response</b></th>
                                <th class="documentation-th">Value</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>status</b></td>
                                <td>
                                    Whether or not the request succeeded
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>error</b></td>
                                <td>
                                    <b>bad_id</b> - incorrect order id<br>
                                    <b>bad_auth</b> - incorrect API key<br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="documentation-example">
                    <pre>
                        {
                         "status":"success"
                     }</pre>
                     <pre>
                        {
                         "status":"fail",
                         "error":"bad_id"
                     }</pre>
                 </div>

                 <h3 id="method-setpartial" class="method-title">Method setPartial</h3>
                 <p>Setting Partially completed status for an order and refunding balance for remains quantity.</p>
                 <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th">Parameters</th>
                                <th class="documentation-th">Description</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>key</b></td>
                                <td>Your Admin API key</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>action</b></td>
                                <td>Method name</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>id</b></td>
                                <td>Order id</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>remains</b></td>
                                <td>Remains quantity</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th"><b>Response</b></th>
                                <th class="documentation-th">Value</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>status</b></td>
                                <td>
                                    Whether or not the request succeeded
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>error</b></td>
                                <td>
                                    <b>bad_id</b> - incorrect order id<br>
                                    <b>bad_auth</b> - incorrect API key<br>
                                    <b>bad_remains</b> - incorrect remains quantity
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="documentation-example">
                    <pre>
                        {
                         "status":"success"
                     }</pre>
                     <pre>
                        {
                         "status":"fail",
                         "error":"bad_id"
                     }</pre>
                 </div>

                 <h3 id="method-setcompleted" class="method-title">Method setCompleted</h3>
                 <p>Setting Completed status for an order.</p>
                 <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th">Parameters</th>
                                <th class="documentation-th">Description</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>key</b></td>
                                <td>Your Admin API key</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>action</b></td>
                                <td>Method name</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>id</b></td>
                                <td>Order id</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th"><b>Response</b></th>
                                <th class="documentation-th">Value</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>status</b></td>
                                <td>
                                    Whether or not the request succeeded
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>error</b></td>
                                <td>
                                    <b>bad_id</b> - incorrect order id<br>
                                    <b>bad_auth</b> - incorrect API key
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="documentation-example">
                    <pre>
                        {
                         "status":"success"
                     }</pre>
                     <pre>
                        {
                         "status":"fail",
                         "error":"bad_id"
                     }</pre>
                 </div>


                 <h3 id="method-setremains" class="method-title">Method setRemains</h3>
                 <p>Setting Remains quantity value for an order.</p>
                 <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th">Parameters</th>
                                <th class="documentation-th">Description</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>key</b></td>
                                <td>Your Admin API key</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>action</b></td>
                                <td>Method name</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>id</b></td>
                                <td>Order id</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>remains</b></td>
                                <td>Remains quantity</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th"><b>Response</b></th>
                                <th class="documentation-th">Value</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>status</b></td>
                                <td>
                                    Whether or not the request succeeded
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>error</b></td>
                                <td>
                                    <b>bad_id</b> - incorrect order id<br>
                                    <b>bad_auth</b> - incorrect API key<br>
                                    <b>bad_remains</b> - incorrect remains quantity
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="documentation-example">
                    <pre>
                        {
                         "status":"success"
                     }</pre>
                     <pre>
                        {
                         "status":"fail",
                         "error":"bad_id"
                     }</pre>
                 </div>


                 <h3 id="method-addpayment" class="method-title">Method addPayment</h3>
                 <p>Setting Completed status for a payment.</p>
                 <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th">Parameters</th>
                                <th class="documentation-th">Description</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>key</b></td>
                                <td>Your Admin API key</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>action</b></td>
                                <td>Method name</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>username / id</b></td>
                                <td>Username or ID</td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>amount</b></td>
                                <td>Amount</td>
                            </tr>
                            <tr class="whiten">
                                <td><b>details</b></td>
                                <td>Details</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr class="grayen">
                                <th class="admin-api-title documentation-th"><b>Response</b></th>
                                <th class="documentation-th">Value</th>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>status</b></td>
                                <td>
                                    Whether or not the request succeeded
                                </td>
                            </tr>
                            <tr class="whiten">
                                <td class="admin-api-title"><b>error</b></td>
                                <td>
                                    <b>bad_username</b> - user not found<br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="documentation-example">
                    <pre>
                        {
                         "status":"success"
                     }</pre>
                     <pre>
                        {
                         "status":"fail",
                         "error":"bad_username"
                     }</pre>
                 </div>




             </div>
         </div>


     </div>
 </div>
</div>
<!-- Themes twig docs END -->
<script src="js/jquery.min3d00.js?v=1635516356"></script>
<script src="js/libs/sticky3d00.js?v=1635516356"></script>
<script src="../code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="assets/6b0e1747/yiibf08.js?v=1635521647"></script>
<script src="assets/7c9d13f7/js/bootstrapbf08.js?v=1635521647"></script>
<script src="assets/b2b1b357/bootstrap-notify.mine183.js?v=1635254385"></script>
<script src="js/admin/nav-priority3d00.js?v=1635516356"></script>
<script src="js/underscore3d00.js?v=1635516356"></script>
<script src="js/admin3d00.js?v=1635516356"></script>
<script>window.modules.layouts = {"theme_id":1,"auth":0};
window.modules.adminApiController = [];</script>  </body>
</html>
