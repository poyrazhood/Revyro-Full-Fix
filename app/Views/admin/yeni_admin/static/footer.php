<div class="py-5"></div>
<div class="modal modal-center fade" id="confirmChange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     data-backdrop="static">
    <div class="modal-dialog modal-dialog-center" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>İşlemi onaylıyor musunuz?</h4>
                <div align="center">
                    <a class="btn btn-primary" href="" id="confirmYes">Evet</a>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Hayır</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade in" id="managerModal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title">Glycon Manager</h4>
            </div>
            <div class="modal-body" style="padding: 0px;">
                <iframe src="<?php echo base_url("admin/manager"); ?>"
                        style="width:100%;height: 700px;border:none;overflow:hidden;"></iframe>
            </div>

        </div>

    </div>
</div>
<div class="modal fade in" id="modalDiv" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <i data-bs-dismiss="modal" aria-label="Close" class="fas fa-times text-light"
                   style="cursor:pointer;"></i>
            </div>
            <div class="modal-body" id="modalContent">
            </div>

        </div>
    </div>
</div>
<div id="sound"></div>
<script>
    $(document).ready(function () {
        window.base_url = '<?= base_url()?>/';
        window.base_url2 = '<?= base_url()?>';
    });
</script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<!-- Bootstrap -->
<!-- CodeMirror -->
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.56.0/codemirror.min.js"></script>
<script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.56.0/mode/xml/xml.min.js"></script>
<!-- CodeMirror -->
<!-- FontAwesome -->
<script type="text/javascript" src="https://kit.fontawesome.com/1bf8354f0a.js"></script>
<!-- FontAwesome -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link href="//gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="//gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="<?= base_url('assets') ?>/js/admin/bootstrap-select.js"></script>
<script type="text/javascript" src="<?= base_url('assets') ?>/js/datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/32.0.0/classic/ckeditor.js"></script>

<script type="text/javascript"
        src="<?= base_url('assets') ?>/js/datepicker/locales/bootstrap-datepicker.en.min.js"></script>
<script src="<?= base_url('assets') ?>/js/admin/toastDemo.js"></script>
<script src="<?= base_url('assets') ?>/js/admin/script.js?n=<?= rand(1111, 9999) ?>>"></script>
<script src="<?= base_url('assets') ?>/js/admin/script-2.js"></script>
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="<?= base_url('assets') ?>/js/admin/jquery.tinytoggle.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    document.addEventListener('focusin', (e) => {
         if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
         e.stopImmediatePropagation();
   }
</script>
<script type="text/javascript">
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
<?php if ($route == "providers") { ?>

    <script>
        $(function () {
            $.ajax({
                url: "<?=base_url("api_balance")?>",
                type: 'GET',
                success: function (result) {
                    $('.providers').html(result);
                    $.ajax({
                        url: "<?=base_url("api_balance?q=1")?>",
                        type: 'GET',
                        success: function (result) {
                            $('.providers').html(result);
                        }
                    });
                }
            });
        });

        $(".providers").html('<div class="balance"></div>');


    </script>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2-1').select2();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2-2').select2();
    });
</script>
<script type="text/javascript">

    $(document).ready(function () {

        var base_url = '<?= base_url()?>';
        <?php if ($route == "new-service" || $route == "new-subscription"): echo '$(document).ready(function(){
getProviderServices($("#provider").val(),base_url);
});'; endif; ?>

        $('#summernote').summernote({
            height: 300,
            tabsize: 2
        });


        $(".service-sortable").sortable({
            handle: '.handle',
            update: function (event, ui) {
                var array = [];
                $(this).find('tr').each(function (i) {
                    $(this).attr('data-line', i + 1);
                    var params = {};
                    params['id'] = $(this).attr('data-id');
                    params['line'] = $(this).attr('data-line');
                    array.push(params);
                });
                $.post(base_url + '/admin/ajax_data', {action: 'service-sortable', services: array});
            }
        });

        $(".methods-sortable").sortable({
            handle: '.handle',
            update: function (event, ui) {
                var array = [];
                $(this).find('tr').each(function (i) {
                    $(this).attr('data-line', i + 1);
                    var params = {};
                    params['id'] = $(this).attr('data-id');
                    params['line'] = $(this).attr('data-line');
                    array.push(params);
                });
                $.post(base_url + 'admin/ajax_data', {action: 'paymentmethod-sortable', methods: array});
            }
        });

        $(".category-sortable").sortable({
            handle: '.handle',
            update: function (event, ui) {
                var array = [];
                $(this).find('.categories').each(function (i) {
                    $(this).attr('data-line', i + 1);
                    var params = {};
                    params['id'] = $(this).attr('data-id');
                    params['line'] = $(this).attr('data-line');
                    array.push(params);
                });
                $.post(base_url + '/admin/ajax_data', {action: 'category-sortable', categories: array});
            }
        });

    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    <?php if( $route == "themes"): ?>
    (function () {
        var codeMirroSetting = {},
            codeType = '<?=$codeType;?>';

        switch (codeType) {
            case 'twig':
                codeMirroSetting = {
                    mode: "text/html",
                    lineNumbers: true,
                    profile: 'xhtml',
                    lineWrapping: true,
                    extraKeys: {
                        "Ctrl-Q": function (cm) {
                            cm.foldCode(cm.getCursor());
                        }
                    },
                    foldGutter: true,
                    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                    onKeyEvent: function (i, e) {
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
                    mode: "text/css",
                    lineNumbers: true,
                    lineWrapping: true,
                    extraKeys: {
                        "Ctrl-Q": function (cm) {
                            cm.foldCode(cm.getCursor());
                        }
                    },
                    foldGutter: true,
                    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                    onKeyEvent: function (i, e) {
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
                    mode: "text/javascript",
                    lineNumbers: true,
                    lineWrapping: true,
                    extraKeys: {
                        "Ctrl-Q": function (cm) {
                            cm.foldCode(cm.getCursor());
                        }
                    },
                    foldGutter: true,
                    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                    onKeyEvent: function (i, e) {
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
                    lineNumbers: true,
                    lineWrapping: true,
                    foldGutter: true,
                    gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                    onKeyEvent: function (i, e) {
// Hook into F11
                        if ((e.keyCode == 122 || e.keyCode == 27) && e.type == 'keydown') {
                            e.stop();
                            return toggleFullscreenEditing();
                        }
                    },
                };
                break;
        }

        CodeMirror.fromTextArea(document.getElementById("code"), codeMirroSetting);

        function toggleFullscreenEditing() {
            var editorDiv = $('.CodeMirror-scroll');
            if (!editorDiv.hasClass('fullscreen')) {
                toggleFullscreenEditing.beforeFullscreen = {height: editorDiv.height(), width: editorDiv.width()}
                editorDiv.addClass('fullscreen');
                editorDiv.height('100%');
                editorDiv.width('100%');
                editor.refresh();
                editorDiv.append('<div class="fullscreen-blockFull"><a href="#" class="btn btn-sm btn-default fullScreenButtonOff"><span class="fa fa-compress" style="font-size: 18px; position: absolute; left: 6px; top: 4px;"></span></a> </div>')
            } else {
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
        $(document).keyup(function (e) {
            if (e.keyCode == 27 && $('.fullscreen').length >= 1) {
                toggleFullscreenEditing();
            }
        });
    })();
    <?php endif; ?>
</script>
<script>
    function guncelleme_denetle() {
        $.post("<?= base_url("admin/version_check")?>", {}, function (data) {
            if (data.status == 200) {
                if (data.current_version != data.new_version) {

                    swal({
                        text: 'Sisteminiz Güncel Değil',

                        button: {
                            text: "Güncelle!",
                            closeModal: false,
                        },
                    })
                        .then(name => {
                            if (!name) throw null;
                            fetch("<?= base_url("admin/update_version")?>");
                            fetch("<?= base_url("admin/create_db_update")?>");
                            return fetch("<?= base_url("admin/version_update_settings")?>");
                        })
                        .then(results => {
                            return results.json();
                        })
                        .then(json => {
                            swal({
                                title: "Sistem Başarıyla Güncellendi",
                            });
                        })
                        .catch(err => {
                            swal({
                                title: "Sistem Başarıyla Güncellendi",
                            });
                        });

                } else {
                    swal({
                        icon: 'success',
                        title: 'Sisteminiz Zaten Son Sürüm',
                    })
                }


            }
            console.log(data.ticket_id);
        }, 'json');
    }
    // Prevent Bootstrap dialog from blocking focusin
document.addEventListener('focusin', (e) => {
  if (e.target.closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
    e.stopImmediatePropagation();
  }
});
</script>
</body>
</html>