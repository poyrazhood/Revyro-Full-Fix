<?= view('admin/static/header'); ?>

    <div class="container container-md"> <div class="row"><div class="col-md-12">
                  <ul class="nav nav-tabs"> <a href="/admin/tickets" class="details_backButton btn btn-link"><span>‹</span> Geri</a><li class="pull-right custom-search">
         <form class="form-inline" action="<?=base_url("admin/orders")?>" method="get" target="_blank">
            <div class="input-group">
               <input type="text" name="search" class="form-control" value="<?=$search_word?>" placeholder="Sipariş numaranız">
               <span class="input-group-btn search-select-wrap">
                  <select class="form-control search-select" name="search_type">
                     <option value="order_id" <?php if( $search_where == "order_id" ): echo 'selected'; endif; ?> >ID</option>
                  </select>
                  <button type="submit" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
               </span>
            </div>
         </form>
      </li></ul> 
                <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
          
        <?php endif; ?>   </div> </div>
   <div class="row">
       <div class="col-md-12">
        <div class="ticket-header__title border-radius-top">
        <div class="row">
            <div class="col-md-12">
                <div class="ticket-header-title">
                  <?php echo $ticketMessage[0]["subject"]; echo ' <span class="service-block__provider-value">';
               if($ticketMessage[0]["support_new"] == 2){
                                           echo'  <i class="fa fa-eye-slash"></i> Henüz Görülmedi';
                                           }elseif ($ticketMessage[0]["support_new"] == 1){
                                           echo'  <i class="fa fa-eye"></i> Görüldü';
                                           } ?></span>
               <?php if( $ticketMessage[0]["canmessage"] == 1 ): ?>
               <span class="badge"><i class="fa fa-lock"></i> Destek talebi kilitlendi, Kullanıcı cevap veremez.</span>
               <?php endif; ?>
                    <div class="ticket-header-id">ID: <?=$ticketMessage[0]["ticket_id"]?></div>
                </div>
            </div>
        </div>
    </div>

            <div class="row">
               <div class="col-md-12">
                   
                  <div class="ticket-header__textarea">
                     <div class="row">
                        <div class="col-md-12">
                              <form action="<?php echo base_url("admin/tickets/read/".$ticketMessage[0]["ticket_id"]) ?>" method="post">
                              <div class="col-md-12">
                                 <div class="ticket-message-submit">
                                    <textarea name="message" id="" cols="30" rows="5" class="form-control ticket-edit__textarea"></textarea>
                                 </div>
                              </div>
                           <input name="username" value="<?=$user["username"]?>" hidden>
                                 <div class="col-sm-6">
                                                    <button class="btn btn-primary" type="submit">
                                Cevabı gönder                            </button>
                                                                            <div class="btn-group">
                                                                              <?php  if( $ticketMessage[0]["status"] != "closed" ): ?>
                                <a href="<?php echo base_url("admin/tickets/close/".$ticketMessage[0]["ticket_id"]) ?>" class="btn btn-default" data-toggle="modal">
                                    Talebi Kapat                                </a>
                                    <?php endif; ?>
                                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                                  <?php if( $ticketMessage[0]["canmessage"] == 2 ): ?>
                                    <li> <a href="<?php echo base_url("admin/tickets/lock/".$ticketMessage[0]["ticket_id"]) ?>"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Destek talebini kilitle">Talebi Kilitle Ve Kapat</a>
                                 </li>  <?php else: ?>
                                     <li><a href="<?php echo base_url("admin/tickets/unlock/".$ticketMessage[0]["ticket_id"]) ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="Destek talebi kilidini aç">Talebin kilidini Aç</a>
                                </li>   <?php endif; ?>
                                                                            </ul>
                                                            </div>
                                            </div>
                                </form>                                    <div class="col-sm-6 ticket-header__unread">
                        <a href="<?php echo base_url("admin/tickets/unread/".$ticketMessage[0]["ticket_id"]) ?>"
                           class="btn btn-link ticket-btn__unread">
                            Okunmamış Olarak İşaretle                        </a>
                    </div>
                            </div></div>
                           
                            </div></div></div>
                                <div class="row">
            <div class="col-md-12">
                          <div class="ticket-body">
                           <div class="ticket-message__container">
                              <?php foreach($ticketMessage as $message): if( $message["support"] == 2 ): ?>
                              <div class="ticket-message__block ticket-message__support">
                                 <div class="ticket-message">
                                    <div class="ticket-message__title">
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <strong><?=$message["support_team"]?></strong>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="ticket-message__title-date">
                                          <?=$message["time"]?>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="ticket-message__text"><?=$message["message"]?></div>
                                 </div>                                          

<a data-toggle="modal" data-target="#modalDiv" data-action="edit_ticket" data-id="<?=$message['id']?>">Düzenle</a> • <a href="#"data-toggle="modal" data-target="#confirmChange" data-href="/admin/tickets/delete/<?=$message['id']?>/<?=$message['ticket_id']?>">Sil</a>

                              </div>
                              <?php else: ?>
                                           <div class="ticket-message__block ticket-message__client">
                                 <div class="ticket-message">
                                    <div class="ticket-message__title">
                                       <div class="row">
                                          <div class="col-sm-6">
                                             <strong><?=$message["username"]?></strong>
                                          </div>
                                          <div class="col-sm-6">
                                             <div class="ticket-message__title-date">
                                                <?=$message["time"]?>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="ticket-message__text"><?=str_replace("</script>","</ script >",str_replace("<script>","< script >",$message["message"]))?></div>
                                 </div>
                              </div>
                              <?php endif; endforeach; ?>
                           </div>
                        
                        </div>
                     </div>
                  </div>
            </div>
         </div>
      </div>
   </div>
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
