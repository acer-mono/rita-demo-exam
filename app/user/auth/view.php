<div class="row">
    <div class="col">
        <h3 class="text-center">Вход</h3>
    </div>
</div>
<?php if (isset($errors) && is_array($errors)): ?>
    <div style="color:red">
        <?php foreach ($errors as $error): ?>
            <?= $error ?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<login-page />


