<?= view('admin/static/header'); ?>

<div class="container-fluid">
   <ul class="nav nav-tabs p-b">
        <li class="<?php if( $action == "profit" ): echo "active"; endif; ?>"><a href="<?php echo base_url("admin/reports") ?>">Siparişlerden kazanç</a></li>
        <li class="<?php if( $action == "payments" ): echo "active"; endif; ?>"><a href="<?php echo base_url("admin/reports/payments") ?>">Ödemelerden kazanç</a></li>
        <li class="<?php if( $action == "orders" ): echo "active"; endif; ?>"><a href="<?php echo base_url("admin/reports/orders") ?>">Sipariş sayısı</a></li>
  


     <form class="" action="<?php echo base_url("admin/reports/".$action."?year=".$year) ?>" method="post">

<li class="pull-right">
              <div class="btn-group" role="group">
            <?php foreach($yearList as $yearl): ?>
              <a href="<?php echo base_url("admin/reports/".$action."?year=".$yearl) ?>" class="btn btn-default <?php if( $yearl == $year ): echo "active"; endif; ?> ">
                  <?php echo $yearl; ?>
              </a>
            <?php endforeach; ?>
          </div>
       </li>

       <?php if( $action == "payments" ): ?>
<li class="pull-right">
           <select class="selectpicker" data-actions-box="true" data-live-search="true" name="methods[]" multiple="" data-max-options="100" data-size="10" title="Ödeme Yöntemleri" tabindex="-98">
             <?php foreach($methods as $method ): ?>
                <option value="<?php echo $method["id"]; ?>" <?php if( $_POST ): if( in_array($method["id"],$_POST["methods"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>> <?php echo $method["method_name"] ?> </option>
              <?php endforeach; ?>
          </select>
</li>
       <?php endif; ?>

       <?php if( $action == "profit" || $action == "orders" ): ?>
<li class="pull-right">
           <select class="selectpicker" data-actions-box="true" data-live-search="true" name="services[]" multiple="" data-max-options="100" data-size="10" title="Servisler" tabindex="-98">
             <?php $c=0;foreach($serviceList as $category => $services ): $c++; ?>
                <optgroup label="<?=$category?>">
                  <?php if( !empty($services[0]["service_id"]) ): ?>
                    <?php for($i=0;$i<count($services);$i++): ?>
                      <option value="<?php echo $services[$i]["service_id"]; ?>" <?php if( $_POST ): if( in_array($services[$i]["service_id"],$_POST["services"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>> <?php echo $services[$i]["service_id"]." - ".$services[$i]["service_name"] ?> </option>
                    <?php endfor; ?>
                  <?php endif; ?>
                </optgroup>
              <?php endforeach; ?>
          </select>
</li>
         <li class="pull-right">
           <select class="selectpicker" name="statuses[]" multiple="" data-max-options="100" data-size="10" title="Sipariş Durumu" tabindex="-98">
             <option value="cron" <?php if( $_POST ): if( in_array("cron",$_POST["statuses"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>>Cron bekleniyor</option>
             <option value="fail"  <?php if( $_POST ): if( in_array("fail",$_POST["statuses"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>>Fail</option>
             <option value="pending"  <?php if( $_POST ): if( in_array("pending",$_POST["statuses"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>>Sipariş alındı</option>
             <option value="inprogress"  <?php if( $_POST ): if( in_array("inprogress",$_POST["statuses"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>>Yükleniyor</option>
             <option value="completed"  <?php if( $_POST ): if( in_array("completed",$_POST["statuses"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>>Tamamlandı</option>
             <option value="partial"  <?php if( $_POST ): if( in_array("partial",$_POST["statuses"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>>Kısmen Tamamlandı</option>
             <option value="canceled"  <?php if( $_POST ): if( in_array("canceled",$_POST["statuses"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>>İptal</option>
             <option value="processing"  <?php if( $_POST ): if( in_array("processing",$_POST["statuses"]) ): echo 'selected'; endif; else: echo 'selected'; endif; ?>>Gönderim Sırasında</option>
           </select>
         </li>

       <?php endif; ?>

      <li class="pull-right">
            <button type="submit" class="btn btn-primary">
              Güncelle
            </button>
        </li>
     </ul>
     </form>

   <div class="row">
      <div class="col-md-12">
         <table class="table report-table" style="border:1px solid #ddd">
            <thead>
               <tr>
                  <th>
                  </th>
                  <th align="right" style="text-align:center;">Ocak</th>
                  <th align="right" style="text-align:center;">Şubat</th>
                  <th align="right" style="text-align:center;">Mart</th>
                  <th align="right" style="text-align:center;">Nisan</th>
                  <th align="right" style="text-align:center;">Mayıs</th>
                  <th align="right" style="text-align:center;">Haziran</th>
                  <th align="right" style="text-align:center;">Temmuz</th>
                  <th align="right" style="text-align:center;">Ağustos</th>
                  <th align="right" style="text-align:center;">Eylül</th>
                  <th align="right" style="text-align:center;">Ekim</th>
                  <th align="right" style="text-align:center;">Kasım</th>
                  <th align="right" style="text-align:center;">Aralık</th>
               </tr>
            </thead>
            <tbody>
              <?php if( $action == "profit" ): ?>
                <?php for ($day=1; $day <=31; $day++): ?>
                 <tr>
                    <td align="center"><?=$day?></td>
                    <?php for( $month=1; $month<=12; $month++ ): ?>
                      <td align="center">
                         <?php echo isset($_POST["services"])?dayCharge($day,$month,$year,["services"=>$_POST["services"],"status"=>$_POST["statuses"]]):"0"; ?>
                      </td>
                    <?php endfor; ?>
                 </tr>
               <?php endfor; ?>
               <tr>
                 <td align="center"><b>Brüt Kazanç: </b></td>
                 <?php for( $month=1; $month<=12; $month++ ): ?>
                   <td align="center">
                     <b>  <?php echo  isset($_POST["services"])?monthCharge($month,$year,["services"=>$_POST["services"],"status"=>$_POST["statuses"]]):'0'; ?> </b>
                   </td>
                 <?php endfor; ?>
               </tr>
               <tr>
                 <td align="center"><b>Net Kazanç: </b></td>
                 <?php for( $month=1; $month<=12; $month++ ): ?>
                   <td align="center">
                     <b>  <?php echo  isset($_POST["services"])?monthChargeNet($month,$year,["services"=>$_POST["services"],"status"=>$_POST["statuses"]]):'0'; ?> </b>
                   </td>
                 <?php endfor; ?>
               </tr>
              <?php elseif( $action == "payments" ): ?>
               <?php for ($day=1; $day <=31; $day++): ?>
                <tr>
                   <td align="center"><?=$day?></td>
                   <?php for( $month=1; $month<=12; $month++ ): ?>
                     <td align="center">
                        <?php echo  isset($_POST["services"])?dayPayments($day,$month,$year,["methods"=>$_POST["methods"]]):'0'; ?>
                     </td>
                   <?php endfor; ?>
                </tr>
                <?php endfor; ?>
                <tr>
                  <td align="center"><b>Toplam: </b></td>
                  <?php for( $month=1; $month<=12; $month++ ): ?>
                    <td align="center">
                      <b>  <?php echo  isset($_POST["services"])?monthPayments($month,$year,["methods"=>$_POST["methods"]]):'0'; ?> </b>
                    </td>
                  <?php endfor; ?>
                </tr>
              <?php elseif( $action == "orders" ): ?>
               <?php for ($day=1; $day <=31; $day++): ?>
                <tr>
                   <td align="center"><?=$day?></td>
                   <?php for( $month=1; $month<=12; $month++ ): ?>
                     <td align="center">
                        <?php echo isset($_POST["services"])?dayOrders($day,$month,$year,["services"=>$_POST["services"],"status"=>$_POST["statuses"]]):'0'; ?>
                     </td>
                   <?php endfor; ?>
                </tr>
                <?php endfor; ?>
                <tr>
                  <td align="center"><b>Toplam: </b></td>
                  <?php for( $month=1; $month<=12; $month++ ): ?>
                    <td align="center">
                      <b>  <?php echo isset($_POST["services"])?monthOrders($month,$year,["services"=>$_POST["services"],"status"=>$_POST["statuses"]]):'0'; ?> </b>
                    </td>
                  <?php endfor; ?>
                </tr>
              <?php endif; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>
<?= view('admin/static/footer'); ?>
