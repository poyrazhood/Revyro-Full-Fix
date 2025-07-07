<?= view('admin/static/header'); ?>
<div class="container-fluid">
        <div class="col-md-8 col-md-offset-2">

                <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
    
   <ul class="nav nav-tabs p-b">
     
     <li class="pull-right custom-search">
        <form class="form-inline" action="<?=base_url("admin/child-panels")?>" method="get">
           <div class="input-group">
              <input type="text" name="search" class="form-control" value="<?=$search_word?>" placeholder="Panel ara...">
              <span class="input-group-btn search-select-wrap">
                 <select class="form-control search-select" name="search_type">
                    <option value="username" <?php if( $search_where == "username" ): echo 'selected'; endif; ?> >Kullanıcı Adı</option>
                    <option value="domain" <?php if( $search_where == "domain" ): echo 'selected'; endif; ?>>Alan Adı</option>
                 </select>
                 <button type="submit" class="btn btn-default"><span class="fa fa-search" aria-hidden="true"></span></button>
              </span>
           </div>
        </form>
     </li>
  </ul>

   <div class="row">
      <div class="col-lg-12">
                  <table class="table">
                     <thead>
                        <tr>
                           <th class="p-l">ID</th>
                           <th>Kullanıcı Adı</th>
                           <th>Domain</th>
                           <th>Para Birimi</th>
                           <th>Ödenen Ücret</th>
                           <th>Sipariş Tarihi</th>
                           <th></th>
                        </tr>
                     </thead>
                       <tbody>
                         <?php foreach($panels as $panel): ?>
                          <tr>
                             <td class="p-l"><?php echo $panel["id"] ?></td>
                             <td><?php echo $panel["username"] ?></td>
                             <td><?php echo $panel["panel_domain"] ?></td>
                             <td><?php echo $panel["panel_currency"] ?></td>
                             <td><?php echo $panel["panel_price"] ?></td>

                             <td  nowrap=""><?php echo $panel["panel_created"] ?></td>
                                           <td class="service-block__action">
                   <div class="dropdown pull-right">
                     <button type="button" class="btn btn-default btn-xs dropdown-toggle btn-xs-caret" data-toggle="dropdown">İşlemler <span class="caret"></span></button>
                     <ul class="dropdown-menu">
     <li  ><a href="#" data-toggle="modal" data-target="#confirmChange" data-href="<?=base_url("admin/child-panels/cancel/".$panel["id"])?>" >İptal ve İade Et</a></li>
                      
                     </ul>
                   </div>
                 </td>
                          </tr>
                        <?php endforeach; ?>
                       </tbody>
                  </table>
            </div>
         </div>
         <?php if( $paginationArr["count"] > 1 ): ?>
           <div class="row">
              <div class="col-sm-8">
                 <nav>
                    <ul class="pagination">
                      <?php if( $paginationArr["current"] != 1 ): ?>
                       <li class="prev"><a href="<?php echo base_url("admin/logs/1/".$search_link) ?>">&laquo;</a></li>
                       <li class="prev"><a href="<?php echo base_url("admin/logs/".$paginationArr["previous"]."/".$search_link) ?>">&lsaquo;</a></li>
                       <?php
                           endif;
                           for ($page=1; $page<=$pageCount; $page++):
                             if( $page >= ($paginationArr['current']-9) and $page <= ($paginationArr['current']+9) ):
                       ?>
                       <li class="<?php if( $page == $paginationArr["current"] ): echo "active"; endif; ?> "><a href="<?php echo base_url("admin/logs/".$page."/".$status.$search_link) ?>"><?=$page?></a></li>
                       <?php endif; endfor;
                             if( $paginationArr["current"] != $paginationArr["count"] ):
                       ?>
                       <li class="next"><a href="<?php echo base_url("admin/logs/".$paginationArr["next"]."/".$search_link) ?>" data-page="1">&rsaquo;</a></li>
                       <li class="next"><a href="<?php echo base_url("admin/logs/".$paginationArr["count"]."/".$search_link) ?>" data-page="1">&raquo;</a></li>
                       <?php endif; ?>
                    </ul>
                 </nav>
              </div>
           </div>
         <?php endif; ?>
</div>
</div>
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
   <div class="modal-dialog modal-dialog-center" role="document">
      <div class="modal-content">
         <div class="modal-body text-center">
            <h4>İşlemi Onaylıyor Musun?</h4>
            <div align="center">
               <a class="btn btn-primary" href="" id="confirmYes">Evet</a>
               <button type="button" class="btn btn-default" data-dismiss="modal">Hayır</button>
            </div>
         </div>
      </div>
   </div>
</div>
<?= view('admin/static/footer'); ?>
