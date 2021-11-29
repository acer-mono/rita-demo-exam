<h1>Вход</h1>
<?php if (isset($errors) && is_array($errors)): ?>
<div style="color:red">
    <?php foreach ($errors as $error): ?>
        <?= $error ?><br>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<form action="/login" method="POST">
    <input type="text" name="login">
    <input type="text" name="password">
    <button>Войти</button>
</form>