<div class="row">
    <div class="col">
        <h3 class="text-center">Регистрация</h3>
    </div>
</div>
<?php if (!empty($errors)): ?>
    <div style="color: red">
        <?php foreach ($errors as $error): ?>
            <?= $error ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<registration-page />