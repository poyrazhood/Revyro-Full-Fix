<div class="col-md-8">
    
<ul class="nav nav-tabs">
    <li class="p-b">
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalDiv" data-action="new_news">Yeni Duyuru Ekle</button>        
 </li>
  </ul>
  
   <table class="table report-table">
      <thead>
         <tr>
            <th><div style="float:left;">Duyuru İkonu</div></th>
            <th>Duyuru Başlığı</th>
            <th>Duyuru Tarihi</th>
            <th></th>
         </tr>
      </thead>
      <tbody>
         <?php foreach($newsList as $new): ?>
         <tr>
<td><div style="float:left;"><img src='img/icons/<?=$new["news_icon"]?>.png' widht="32" height="32"></div></td>
     <td><?=$new["news_title"]?></td>
  
  <td><?=$new["news_date"]?></td>
  
            <td class="text-right col-md-1">
              <div class="dropdown pull-right">
             
<button type="button" class="btn btn-default btn-xs pull-right" data-toggle="modal" data-target="#modalDiv" data-action="edit_news" data-id="<?=$new['id']?>">Düzenle</button>         

</div>
            </td>
         </tr>
         <?php endforeach; ?>
      </tbody>
   </table>
</div>