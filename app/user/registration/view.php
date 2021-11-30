<main>
    <?php if (!empty($errors)): ?>
    <div style="color: red">
        <?php foreach ($errors as $error): ?>
            <?= $error ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form action="/register" method="POST">
        <div>
            <label>
                Логин<br>
                <input type="text" name="login">
            </label>
        </div>
        <div>
            <label>
                Почта<br>
                <input type="email" name="email">
            </label>
        </div>
        <div>
            <label>
                Пароль<br>
                <input type="password" name="password">
            </label>
        </div>
        <div>
            <label>
                Имя<br>
                <input type="text" name="name">
            </label>
        </div>
        <button>Зарегистрироваться</button>
    </form>
</main>