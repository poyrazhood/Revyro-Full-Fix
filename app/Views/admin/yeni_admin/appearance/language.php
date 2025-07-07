<?php if( !route(4) ): ?>
<div class="col-md-10">
    <div class="card apperance-hr">
        <div class="card-header p-0">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-header-title">Diller</div>
                    <a href="<?= base_url('admin/appearance/language/new')?>" class="btn-glycon btn-glycon-success btn-card-header"><i class="fas fa-plus"></i> Yeni
                        Dil Oluştur</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach($languageList as $language): ?>
                <div class="col-md-4">
                    <div class="glycon-module-card">
                        <div class="glycon-module-image">
                            <p> <?php echo $language["language_name"]; ?></p>

                            <a href="<?php echo base_url('admin/appearance/language/'.$language["language_code"]) ?>" class="btn-glycon btn-glycon-primary btn-glycon-module"><i class="fa fa-cog"
                                                                                                   aria-hidden="true"></i>
                                Düzenle</a>
                            <a href="<?= ( $language["language_type"] == 1 )?base_url('admin/appearance/language/?lang-id='.$language["language_code"].'&lang-type=2'):base_url('admin/appearance/language/?lang-id='.$language["language_code"].'&lang-type=1')?>" class="btn-glycon btn-glycon-<?= $language["language_type"] == 2?'danger':'success' ?> btn-glycon-module"><i class="fas fa-<?= $language["language_type"] == 2?'times':'check' ?>"
                                                                                                  aria-hidden="true"></i>
                                <?php if( $language["language_type"] == 2 ): echo 'Pasifleştir';else: echo 'Aktifleştir';endif;  ?></a> 
                                <?php if(!$language['default_language'] && $language["language_type"] == 2): ?>
                                <a href="<?= base_url('admin/appearance/language/?lang-id='.$language["language_code"].'&lang-default=1&lang-short='.$language["language_code"])?>" class="btn-glycon btn-glycon-success btn-glycon-module"><i class="fas fa-<?= $language["language_type"] == 2?'times':'check' ?>"
                                                                                                  aria-hidden="true"></i>
                                Varsayılan Yap</a>
                                <?php endif; ?>
                        </div>
                    </div>
                </div>

         <?php endforeach; ?>
                
            </div>
        </div>
    </div>
</div>
<?php elseif( route(4) == "new" ): ?>
<div class="col-md-10">
			<div class="card apperance-hr">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12">
							<div class="card-header-title">Türkçe</div>
							<a href="<?= base_url("admin/appearance/language") ?>" class="btn-glycon btn-glycon-success btn-card-header"><i class="fas fa-arrow-left"></i> Geri</a>
						             <div class="col-md-6 text-right">
                                <div class="pull-right">
  <input class="form-control" placeholder="Kelime ara..."  id="myInput" onkeyup="myFunction()" type="text" value="">                                </div>
                            </div>
                        </div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
								<form action="<?php echo base_url('admin/appearance/language/new') ?>" method="post" enctype="multipart/form-data">
						<div class="col-12">
							<div class="language-detail" style="max-height: 600px;overflow-y: scroll;padding:5px 10px;">
									<div class="form-group">
										<label class="control-label">Dil Adı</label>
										<input type="text" class="form-control" name="language" value="Türkçe">
									</div>
                                     <div class="form-group">
              <label class="control-label">Dil Kodu</label>
              <select class="form-control" name="languagecode">
                 <option value="ar">ar (Arabic)</option>
                 <option value="af">af (Afrikaans)</option>
                 <option value="am">am (Amharic)</option>
                 <option value="sq">sq (Albanian)</option>
                 <option value="hy">hy (Armenian)</option>
                 <option value="az">az (Azerbaijani)</option>
                 <option value="eu">eu (Basque)</option>
                 <option value="bn">bn (Bengali)</option>
                 <option value="bg">bg (Bulgarian)</option>
                 <option value="ca">ca (Catalan)</option>
                 <option value="zh-HK">zh-HK (Chinese Hong Kong)</option>
                 <option value="zh-CN">zh-CN (Chinese Simplified)</option>
                 <option value="zh-TW">zh-TW (Chinese Traditional)</option>
                 <option value="hr">hr (Croatian)</option>
                 <option value="cs">cs (Czech)</option>
                 <option value="da">da (Danish)</option>
                 <option value="nl">nl (Dutch)</option>
                 <option value="en-GB">en-GB (English UK)</option>
                 <option value="en">en (English US)</option>
                 <option value="et">et (Estonian)</option>
                 <option value="fil">fil (Filipino)</option>
                 <option value="fi">fi (Finnish)</option>
                 <option value="fr">fr (French)</option>
                 <option value="fr-CA">fr-CA (French Canadian)</option>
                 <option value="gl">gl (Galician)</option>
                 <option value="ka">ka (Georgian)</option>
                 <option value="de">de (German)</option>
                 <option value="de-AT">de-AT (German Austria)</option>
                 <option value="de-CH">de-CH (German Switzerland)</option>
                 <option value="el">el (Greek)</option>
                 <option value="gu">gu (Gujarati)</option>
                 <option value="iw">iw (Hebrew)</option>
                 <option value="hi">hi (Hindi)</option>
                 <option value="hu">hu (Hungarain)</option>
                 <option value="is">is (Icelandic)</option>
                 <option value="id">id (Indonesian)</option>
                 <option value="it">it (Italian)</option>
                 <option value="ja">ja (Japanese)</option>
                 <option value="kn">kn (Kannada)</option>
                 <option value="ko">ko (Korean)</option>
                 <option value="lo">lo (Laothian)</option>
                 <option value="lv">lv (Latvian)</option>
                 <option value="lt">lt (Lithuanian)</option>
                 <option value="ms">ms (Malay)</option>
                 <option value="ml">ml (Malayalam)</option>
                 <option value="mr">mr (Marathi)</option>
                 <option value="mn">mn (Mongolian)</option>
                 <option value="no">no (Norwegian)</option>
                 <option value="fa">fa (Persian)</option>
                 <option value="pl">pl (Polish)</option>
                 <option value="pt">pt (Portuguese)</option>
                 <option value="pt-BR">pt-BR (Portuguese Brazil)</option>
                 <option value="pt-PT">pt-PT (Portuguese Portugal)</option>
                 <option value="ro">ro (Romanian)</option>
                 <option value="ru">ru (Russian)</option>
                 <option value="sr">sr (Serbian)</option>
                 <option value="si">si (Sinhalese)</option>
                 <option value="sk">sk (Slovak)</option>
                 <option value="sl">sl (Slovenian)</option>
                 <option value="es">es (Spanish)</option>
                 <option value="es-419">es-419 (Spanish Latin America)</option>
                 <option value="sw">sw (Swahili)</option>
                 <option value="sv">sv (Swedish)</option>
                 <option value="ta">ta (Tamil)</option>
                 <option value="te">te (Telugu)</option>
                 <option value="th">th (Thai)</option>
                 <option value="tr">tr (Turkish)</option>
                 <option value="uk">uk (Ukrainian)</option>
                 <option value="ur">ur (Urdu)</option>
                 <option value="vi">vi (Vietnamese)</option>
                 <option value="zu">zu (Zulu)</option>
              </select>
           </div>
									<hr>
<div id="myUL">
										<?php foreach( $languageArray as $key => $val ): ?>
											<eg><a>  <div class="form-group">
												<label class="control-label"><?php echo $key; ?></label>
												<input type="text" class="form-control" name="Language[<?php echo $key; ?>]" value="<?php echo $val;?>">
											</div></a></eg>
										<?php endforeach; ?>
									</div>
							</div>
							<button type="submit" class="glycon-badge badge-success pointer text-decoration-none my-3 w-100">Ekle</button>
								</form>
						</div>
					</div>
				</div>
			</div>
		</div>

<?php elseif( route(4)): ?>
<div class="col-md-10">
			<div class="card apperance-hr">
				<div class="card-header p-0">
					<div class="row align-items-center">
						<div class="col-md-12">
							<div class="card-header-title">Türkçe</div>
							<a href="<?= base_url("admin/appearance/language") ?>" class="btn-glycon btn-glycon-success btn-card-header"><i class="fas fa-arrow-left"></i> Geri</a>
						             <div class="col-md-6 text-right">

                                <div class="pull-right" style="margin:auto;">
  <input class="form-control" placeholder="Kelime ara..."  id="myInput" onkeyup="myFunction()" type="text" value="">                                </div>
                            </div>
                        </div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
								<form class="form" data-xhr="true" action="<?php echo base_url('admin/appearance/language/'.route(4)) ?>" method="post" enctype="multipart/form-data">
						<div class="col-12">
							<div class="language-detail" style="max-height: 600px;overflow-y: scroll;padding:5px 10px;">
									<div class="form-group">
										<label class="control-label">Dil Adı</label>
										<input type="text" class="form-control" name="language" value="<?php echo $language["language_name"] ?>">
									</div>

									<hr>

									<div id="myUL">
										<?php foreach( $languageArray as $key => $val ): ?>
											<eg><a>  <div class="form-group">
												<label class="control-label"><?php echo $key; ?></label>
												<input type="text" class="form-control" name="Language[<?php echo $key; ?>]" value="<?php echo $val;?>">
											</div></a></eg>
										<?php endforeach; ?>
									</div>
							</div>
							<button type="submit" class="glycon-badge badge-success pointer text-decoration-none my-3 w-100">Güncelle</button>
								</form>
						</div>
					</div>
				</div>
			</div>
		</div>
<script>
function myFunction() {
  // Declare variables
  var input, filter, ul, eg, a, i, txtValue;
  input = document.getElementById('myInput');
  filter = input.value.toUpperCase();
  ul = document.getElementById("myUL");
  eg = ul.getElementsByTagName('eg');

  // Loop through all egst items, and hide those who don't match the search query
  for (i = 0; i < eg.length; i++) {
    a = eg[i].getElementsByTagName("a")[0];
    txtValue = a.textContent || a.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      eg[i].style.display = "";
    } else {
      eg[i].style.display = "none";
    }
  }
}
</script>
<?php endif; ?>
