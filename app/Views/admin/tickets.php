<?= view('admin/static/header'); ?>
<div class="container-fluid">
            <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
   <ul class="nav nav-tabs">
      <li class="p-b"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalDiv" data-action="new_ticket">Yeni Destek Talebi Oluştur</button></li>
      <li class="pull-right custom-search">
         <form class="form-inline" action="" method="get">
            <div class="input-group">
               <input type="text" name="search" class="form-control" value="<?=$search_word?>" placeholder="Talep Ara...">
               <span class="input-group-btn search-select-wrap">
                  <select class="form-control search-select" name="search_type">
                     <option value="subject" <?php if( $search_where == "subject" ): echo 'selected'; endif; ?> >Konu</option>
                     <option value="client" <?php if( $search_where == "client" ): echo 'selected'; endif; ?> >Kullanıcı Adı</option>
                  </select>
                  <button type="submit" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
               </span>
            </div>
         </form>
      </li>
      <li class="pull-right export-li">
          <?php if($search_read == 'unread'){ ?>
         <a href="<?=base_url("admin/tickets")?>" class="export">
         <span class="export-title">Tümünü Göster</span>
         </a>
         <?php }else{ ?>
                      <a href="<?=base_url("admin/tickets")?>?search=unread" class="export">
         <span class="export-title">Okunmayanları Göster</span>
         </a>
             
        <?php } ?>
      </li>
   </ul>
   <table class="table">
      <thead>
         <tr>
            <th class="checkAll-th">
               <div class="checkAll-holder">
                  <input type="checkbox" id="checkAll">
                  <input type="hidden" id="checkAllText" value="order">
               </div>
               <div class="action-block">
                  <ul class="action-list">
                     <li><span class="countOrders"></span> talep seçildi</li>
                     <li>
                        <div class="dropdown">
                           <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown"> toplu işlemler<span class="caret"></span></button>
                           <ul class="dropdown-menu">
                              <li>
                                 <a class="bulkorder" data-type="unread">Tümünü Okunmamış Yap</a>
                                 <a class="bulkorder" data-type="readed">Tümünü Okundu Yap</a>
                                 <a class="bulkorder" data-type="lock">Tümünü Kilitle</a>
                                 <a class="bulkorder" data-type="unlock">Tümünün Kilidini Aç</a>
                                 <a class="bulkorder" data-type="close">Tümünü Kapat</a>
                                 <a class="bulkorder" data-type="pending">Tümünü Beklemeye Al</a>
                                 <a class="bulkorder" data-type="answered">Tümünü Yanıtladı Yap</a>
                              </li>
                           </ul>
                        </div>
                     </li>
                  </ul>
               </div>
            </th>
            <th width="5%" class="p-l">ID</th>
            <th width="15%">Kullanıcı Adı</th>
            <th width="50%">Konu</th>
            <th width="10%" class="dropdown-th">
               <div class="dropdown">
                  <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  Statü <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                     <li class="active"><a href="<?=base_url("admin/tickets")?>">Hepsi (<?=countRow(["table"=>"tickets"]);?>)</a></li>
                     <li><a href="<?=base_url("admin/tickets")?>?status=pending">Beklemede (<?=countRow(["table"=>"tickets","where"=>["status"=>"pending"]]);?>)</a></li>
                     <li><a href="<?=base_url("admin/tickets")?>?status=answered">Cevaplandı (<?=countRow(["table"=>"tickets","where"=>["status"=>"answered"]]);?>)</a></li>
                     <li><a href="<?=base_url("admin/tickets")?>?status=closed">Kapatıldı (<?=countRow(["table"=>"tickets","where"=>["status"=>"closed"]]);?>)</a></li>
                  </ul>
               </div>
            </th>
            <th width="10%">Oluşturulma Tarihi</th>
            <th width="10%" nowrap="">Son Güncellenme</th>
            <th></th>
         </tr>
      </thead>
      <form id="changebulkForm" action="<?php echo base_url("admin/tickets/multi-action") ?>" method="post">
        <tbody>
          <?php foreach($tickets as $ticket ): ?>
              <tr>
                 <td><input type="checkbox" class="selectOrder" name="ticket[<?php echo $ticket["ticket_id"] ?>]" value="1" style="border:1px solid #fff"></td>
                 <td class="p-l"><?php echo $ticket["ticket_id"] ?></td>
                 <td><?php echo $ticket["username"] ?></td>
                 <td class="subject"><?php  if( $ticket["canmessage"] == 1 ): echo '<i class="fa fa-lock"></i> '; endif;  ?><a href="<?=base_url("admin/tickets/read/".$ticket["ticket_id"])?>"><?php if( $ticket["client_new"] == 2 ): echo "<b>".$ticket["subject"]."</b>"; else: echo $ticket["subject"]; endif; ?></a></td>
                 <td><?php echo ticketStatu($ticket["status"]); ?></td>
                 <td nowrap=""><?php echo $ticket["time"] ?></td>
                 <td nowrap=""><?php echo $ticket["lastupdate_time"] ?></td>
                 <td class="service-block__action">
                   <div class="dropdown pull-right">
                     <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">İşlemler <span class="caret"></span></button>
                     <ul class="dropdown-menu">
                       <?php if( $ticket["client_new"] == 1 ): ?>
                         <li><a href="<?php echo base_url("admin/tickets/unread/".$ticket["ticket_id"]) ?>">Okunmadı Yap</a></li>
                       <?php elseif($ticket["client_new"] == 2): ?>
                         <li><a href="<?php echo base_url("admin/tickets/readed/".$ticket["ticket_id"]) ?>">Okundu Yap</a></li>
                       <?php endif; if( $ticket["canmessage"] == 2 ): ?>
                         <li><a href="<?php echo base_url("admin/tickets/lock/".$ticket["ticket_id"]) ?>">Kilitle</a></li>
                       <?php else: ?>
                         <li><a href="<?php echo base_url("admin/tickets/unlock/".$ticket["ticket_id"]) ?>">Kilidini Aç</a></li>
                       <?php endif; if( $ticket["status"] != "closed" ): ?>
                         <li><a href="<?php echo base_url("admin/tickets/close/".$ticket["ticket_id"]) ?>">Talebi Kapat</a></li>
                       <?php endif; ?>
                     </ul>
                   </div>
                 </td>
              </tr>
            <?php endforeach; ?>
        </tbody>
        <input type="hidden" name="bulkStatus" id="bulkStatus" value="0">
      </form>
   </table>
   <?php if( $paginationArr["count"] > 1 ): ?>
     <div class="row">
        <div class="col-sm-8">
           <nav>
              <ul class="pagination">
                <?php if( $paginationArr["current"] != 1 ): ?>
                 <li class="prev"><a href="<?php echo base_url("admin/tickets/1".$search_link) ?>">&laquo;</a></li>
                 <li class="prev"><a href="<?php echo base_url("admin/tickets/".$paginationArr["previous"].$search_link) ?>">&lsaquo;</a></li>
                 <?php
                     endif;
                     for ($page=1; $page<=$pageCount; $page++):
                       if( $page >= ($paginationArr['current']-9) and $page <= ($paginationArr['current']+9) ):
                 ?>
                 <li class="<?php if( $page == $paginationArr["current"] ): echo "active"; endif; ?> "><a href="<?php echo base_url("admin/tickets/".$page.$search_link) ?>"><?=$page?></a></li>
                 <?php endif; endfor;
                       if( $paginationArr["current"] != $paginationArr["count"] ):
                 ?>
                 <li class="next"><a href="<?php echo base_url("admin/tickets/".$paginationArr["next"].$search_link) ?>" data-page="1">&rsaquo;</a></li>
                 <li class="next"><a href="<?php echo base_url("admin/tickets/".$paginationArr["count"].$search_link) ?>" data-page="1">&raquo;</a></li>
                 <?php endif; ?>
              </ul>
           </nav>
        </div>
     </div>
   <?php endif; ?>
</div>
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
   <div class="modal-dialog modal-dialog-center" role="document">
      <div class="modal-content">
         <div class="modal-body text-center">
            <h4>İşlemi Onaylıyor Musunuz?</h4>
            <div align="center">
               <a class="btn btn-primary" href="" id="confirmYes">Evet</a>
               <button type="button" class="btn btn-default" data-dismiss="modal">Hayır</button>
            </div>
         </div>
      </div>
   </div>
</div>

<?= view('admin/static/footer'); ?>
