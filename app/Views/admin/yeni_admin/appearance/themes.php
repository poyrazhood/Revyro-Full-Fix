<style>
	base_url.twig-editor__base_url div:nth-child(3) {
    
}
	.twig-editor-list-title {
    background: #0c2041;
    color: white;
    font-weight: bold;
    padding: 3px 10px;
    text-align: center;
    border-radius: 5px;
}
ul.twig-editor-list.in{
    padding:1px;
}

ul.twig-editor-list.in li {
    list-style: none;
    margin:1px 10px;
    padding:2px 5px;
    border-radius:5px;
}
ul.twig-editor-list.in li a {
    color:#2c7dff;
}
	.twig-editor-block {
    max-height: 700px;
    overflow-y: scroll;
    background: white;
    padding: 10px;
}
	.twig-editor-list li:before {
    content: "\f016";
    font: normal normal normal 14px/1 FontAwesome;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    font-style: normal;
    font-weight: 400;
    color: #337ab7;
    vertical-align: 0px;
    margin-right: 5px;
}
	.CodeMirror {
    position: relative;
    overflow: hidden;
    background: #fff;
    min-height: 637px;
}
</style>
<?php if( !route(4) || (route(4) == "active" && route(4)) ): ?>
<div class="col-md-10">
			<h3>Temalar</h3>
			<div class="row">
                         <?php foreach($themes as $theme):

            $x = $theme['theme_dirname'];
            $yol = base_url("select-theme/$x");

         ?>
				<div class="col-sm-3">
					<div class="card">

						<img src="<?= base_url("assets/theme-pics/".$x.".png") ?>" class="card-img-top">
						<div class="card-body">
							<h5 class="card-title"><?php echo $theme["theme_name"]; ?></h5>
							<div class="d-grid gap-2">
								<a href="<?php echo base_url('admin/appearance/themes/'.$theme["theme_dirname"]) ?>" class="btn-glycon btn-glycon-primary text-center"><i class="fas fa-check"></i> Düzenle</a>
								<a href="<?php echo base_url('admin/appearance/themes/active/'.$theme["theme_dirname"]) ?>" class="btn-glycon btn-glycon-<?= $settings["site_theme"] != $theme["theme_dirname"]?"success":"danger"?> text-center"><i class="fas fa-<?= $settings["site_theme"] != $theme["theme_dirname"]?"check":"times"?>"></i> <?= $settings["site_theme"] != $theme["theme_dirname"]?"Aktif Et":"Aktif" ?></a>
							</div>
						</div>
					</div>
				</div>
                <?php endforeach; ?>
			</div>
		</div>
<?php elseif( route(4) && route(4) != "active"): ?>
 <div class="col-md-10">
    <div class="panel">
      <div class="panel-heading edit-theme-title"><strong><?php echo $theme["theme_name"] ?></strong> Düzenleniyor</div>

        <div class="row">
          <div class="col-md-3 padding-md-right-null">

            <div class="panel-body edit-theme-body">
              <div class="twig-editor-block">
                <?php
                  $layouts  = [
                    "HTML"=>["header.twig","footer.twig","account.twig","contact.twig","addfunds.twig","api.twig",
                    "login.twig","signup.twig","neworder.twig","orders.twig","dripfeeds.twig","subscriptions.twig",
                    "services.twig","child-panels.twig","tickets.twig","viewticket.twig","blog.twig","blogpost.twig","verify.twig","affiliates.twig", 
                    "resetpassword.twig",
                    "terms.twig","faq.twig","404.twig"],
                    "CSS"=>["bootstrap.css","style.css"],
                    "JS"=>["bootstrap.js","script.js"]
                  ];
                foreach ($layouts as $style => $layout):
                  echo '<div class="twig-editor-list-title" href="#folder_'.$style.'">'.$style.'</div><ul class="twig-editor-list in" id="folder_'.$style.'">';
                  foreach ($layouts[$style] as $layout) :
                    if( $lyt == $layout ):
                      $active = ' class="active file-modified" ';
                    else:
                      $active = '';
                    endif;
                    echo '
                      <li '. $active .'><a href="'.base_url('admin/appearance/themes/'.$theme["theme_dirname"]).'?file='.$layout.'">'.$layout.'</a></li>';
                  endforeach;
                  echo '</ul>';
                endforeach;
              ?>
              </div>

            </div>
          </div>
          <div class="col-md-9 padding-md-left-null edit-theme__block-editor">
            <?php if( !$lyt ): ?>
              <div class="panel-body">
                <div class="row">
                   <div class="col-md-12">
                    <div class="theme-edit-block">
                      <div class="alert alert-info" role="alert">
                     Döküman yakında eklenecektir...<br>
                     Sol taraftan düzenlemek istediğiniz sayfanın tema kodlarına ulaşabilirsiniz.
                      </div>
                    </div>
                  </div>
                  </div>
              </div>
            <?php else: ?>

                  
                  <div id="fullscreen">

               <div class="panel-body">

                <?php
                $file = fopen($fn, "r");
                $size = filesize($fn);
                $text = fread($file, $size);
                $text = str_replace("<","&lt;",$text);
                $text = str_replace(">","&gt;",$text);
                $text = str_replace('"',"&quot;",$text);
                fclose($file);
                ?>

                <div class="row">
                    <div class="col-md-8">
                      <strong class="edit-theme-filename"><?=$dir."/".$lyt?></strong>
                        </div>
                  </div>
           

                <form action="<?php echo base_url("admin/appearance/themes/".$theme["theme_dirname"]."?file=".$lyt) ?>" method="post" class="twig-editor__base_url">

                  <textarea id="code" name="code" class="codemirror-textarea"><?=$text;?></textarea>
                  <div class="edit-theme-body-buttons text-right">
                    <button class="btn btn-primary click">Güncelle</button>
                  </div>
                </form>

              </div>
            <?php endif; ?>
          </div>
        </div>

    </div>
  </div>

<script type="text/javascript">

$(document).ready(function(){

var site_url  = $('head base').attr('href');

$('#summernote').summernote({
height: 300,
tabsize: 2
});


$(".service-sortable").sortable({handle: '.handle',
update: function(event, ui) {
var array = [];
$(this).find('tr').each(function(i) {
$(this).attr('data-line',i+1);
var params = {};
params['id']   = $(this).attr('data-id');
params['line'] = $(this).attr('data-line');
array.push(params);
});
$.post(site_url+'admin/ajax_data',{action:'service-sortable',services:array});
}
});

$(".methods-sortable").sortable({handle: '.handle',
update: function(event, ui) {
var array = [];
$(this).find('tr').each(function(i) {
$(this).attr('data-line',i+1);
var params = {};
params['id']   = $(this).attr('data-id');
params['line'] = $(this).attr('data-line');
array.push(params);
});
$.post(site_url+'admin/ajax_data',{action:'paymentmethod-sortable',methods:array});
}
});

$(".category-sortable").sortable({handle: '.handle',
update: function(event, ui) {
var array = [];
$(this).find('.categories').each(function(i) {
$(this).attr('data-line',i+1);
var params = {};
params['id']   = $(this).attr('data-id');
params['line'] = $(this).attr('data-line');
array.push(params);
});
$.post(site_url+'admin/ajax_data',{action:'category-sortable',categories:array});
}
});

});
$(function () {
$('[data-toggle="tooltip"]').tooltip()
});
(function () {
var codeMirroSetting = {},
codeType = 'twig';

switch (codeType){
case 'twig':
codeMirroSetting = {
mode : "text/html",
lineNumbers : true,
profile: 'xhtml',
lineWrapping: true,
extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
foldGutter: true,
gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
onKeyEvent: function(i, e) {
// Hook into F11
if ((e.keyCode == 122 || e.keyCode == 27) && e.type == 'keydown') {
e.stop();
return toggleFullscreenEditing();
}
},
};
break;
case 'css':
codeMirroSetting = {
mode : "text/css",
lineNumbers : true,
lineWrapping: true,
extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
foldGutter: true,
gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
onKeyEvent: function(i, e) {
// Hook into F11
if ((e.keyCode == 122 || e.keyCode == 27) && e.type == 'keydown') {
e.stop();
return toggleFullscreenEditing();
}
},
};
break;
case 'js':
codeMirroSetting = {
mode : "text/javascript",
lineNumbers : true,
lineWrapping: true,
extraKeys: {"Ctrl-Q": function(cm){ cm.foldCode(cm.getCursor()); }},
foldGutter: true,
gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
onKeyEvent: function(i, e) {
// Hook into F11
if ((e.keyCode == 122 || e.keyCode == 27) && e.type == 'keydown') {
e.stop();
return toggleFullscreenEditing();
}
},
};
break;
default:
codeMirroSetting = {
lineNumbers : true,
lineWrapping: true,
foldGutter: true,
gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
onKeyEvent: function(i, e) {
// Hook into F11
if ((e.keyCode == 122 || e.keyCode == 27) && e.type == 'keydown') {
e.stop();
return toggleFullscreenEditing();
}
},
};
break;
}

function toggleFullscreenEditing()
{
var editorDiv = $('.CodeMirror-scroll');
if (!editorDiv.hasClass('fullscreen')) {
toggleFullscreenEditing.beforeFullscreen = { height: editorDiv.height(), width: editorDiv.width() }
editorDiv.addClass('fullscreen');
editorDiv.height('100%');
editorDiv.width('100%');
editor.refresh();
editorDiv.append('<div class="fullscreen-blockFull"><a href="#" class="btn btn-sm btn-default fullScreenButtonOff"><span class="fa fa-compress" style="font-size: 18px; position: absolute; left: 6px; top: 4px;"></span></a> </div>')
}
else {
editorDiv.removeClass('fullscreen');
editorDiv.height(toggleFullscreenEditing.beforeFullscreen.height);
editorDiv.width(toggleFullscreenEditing.beforeFullscreen.width);
editor.refresh();
$('.fullscreen-blockFull').remove();
}
}

$(document).on('click', '.fullScreenButton', function (e) {
toggleFullscreenEditing();
});
$(document).on('click', '.fullScreenButtonOff', function (e) {
toggleFullscreenEditing();
});
$(document).keyup(function(e) {
if (e.keyCode == 27 && $('.fullscreen').length >= 1) {
toggleFullscreenEditing();
}
});
})();
</script>
<?php endif; ?>
