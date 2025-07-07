<div class="col-md-8">
  <div class="settings-header__table">
    <button type="button"  class="btn btn-default m-b" data-toggle="modal" data-target="#modalDiv" data-action="new_bankaccount" >Yeni banka hesabı</button>
    <div style="float:right;">
     <a href="<?= base_url()?>/admin/settings/payment-methods" class="details_backButton btn btn-link"><span>‹</span> Geri</a></div>


  </div>
   <table class="table">
      <thead>
         <tr>
            <th>
              Banka Adı
            </th>
            <th>
              Alıcı Adı
            </th>
            <th>
              IBAN
            </th>
            <th></th>
         </tr>
      </thead>
      <tbody class="methods-sortable">
         <?php foreach($bankList as $bank): ?>
           <tr>
            <td>
               <?php echo $bank["bank_name"]; ?>
            </td>
            <td><?php echo $bank["bank_alici"]; ?></td>
            <td><?php echo $bank["bank_iban"]; ?></td>
            <td class="p-r">
               <button type="button" class="btn btn-default btn-xs pull-right edit-payment-method" data-toggle="modal" data-target="#modalDiv" data-action="edit_bankaccount" data-id="<?php echo $bank["id"]; ?>">Düzenle</button>
            </td>
         </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>
