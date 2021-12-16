<?php
    $application = json_encode($application);
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h3 class="text-center">Информация о заявке</h3>
        </div>
    </div>
    <div class="row">
        <application-info application-info='<?= $application?>' is-admin="<?=Session::getInstance()->isAdmin()?>" />
    </div>
</div>