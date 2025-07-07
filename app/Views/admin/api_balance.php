<?php if ($user["access"]["providers"]) { ?>

    <?php if (isset($_GET["q"]) == 1) { ?>


        <div class="form-group">


            <table class="table providers_list">
                <thead>
                <tr>
                    <th class="p-l" width="45%">Sağlayıcı</th>
                    <th>Bakiye</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>


                <?php foreach ($api_details

                as $provider): ?>

                <?php

                $balance = $smmapi->action(array('key' => $provider["api_key"], 'action' => 'balance'), $provider["api_url"]);

                $balance1 = isset($balance->balance)?$balance->balance:null;

                $balance2 = isset($balance->currency)?$balance->currency:null;
                if ($balance1 == null) {
                    $error = 1;
                    $call = '<i class="fas fa-question-circle"></i>';
                } else {
                    $error = 0;
                    $call = $balance1 . " " . $balance2;
                }

                ?>
                <tr <?php if ($error == 1): echo 'class="grey"'; endif; ?> class="list_item ">
                    <td class="name p-l"><?php echo $provider["api_name"]; ?> </td>
                    <td><?= $call ?></td>
                    <td class="p-r">

                        <button type="button" class="btn btn-default btn-xs pull-right" data-toggle="modal"
                                data-target="#modalDiv" data-action="edit_provider" data-id="<?= $provider["id"] ?>">
                            Düzenle
                        </button>
                    </td>


                    <input type="hidden" name="privder_changes" value="privder_changes">

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>


    <?php } else { ?>


        <div class="form-group">


            <table class="table providers_list">
                <thead>
                <tr>
                    <th class="p-l" width="45%">Sağlayıcı</th>
                    <th>Bakiye</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>


                <?php foreach ($api_details

                as $provider): ?>


                <tr id="" class="list_item ">
                    <td class="name p-l"><?php echo $provider["api_name"]; ?> </td>
                    <td><i class="fas fa-spinner fa-spin"></i>
                    </td>
                    <td class="p-r">

                        <button type="button" class="btn btn-default btn-xs pull-right" data-toggle="modal"
                                data-target="#modalDiv" data-action="edit_provider" data-id="<?= $provider["id"] ?>">
                            Düzenle
                        </button>
                    </td>


                    <input type="hidden" name="privder_changes" value="privder_changes">
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>


    <?php }
} ?>
