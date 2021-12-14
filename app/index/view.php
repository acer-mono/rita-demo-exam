<?php if(!Session::getInstance()->isAdmin()): ?>
<div class="row">
    <div class="col">
        <h3 class="text-center">Вместе - лучше!</h3>
    </div>
</div>
<div class="row text-center">
    <div class="col-12">
        Переполненные мусорные баки? Бабушка над твоим балконом любит подкармливать голубей?
        Самокат утонул в луже у подъезда? Мы можем помочь друг другу!
    </div>
    <div class="col-12">
        Городской портал "Сделаем лучше вместе" поможет решить тебе любую проблему.
        Три простых шага для обращения:
    </div>
</div>
<div class="row">
    <div class="col-sm-12 col-md-4 pt-5">
        <div class="card border-0 bg-light">
            <img width="120" class="landing-image" src="/public/images/register.png" alt="Register">
            <div class="card-body text-center">
                <h5 class="card-title">Зарегистрируйся</h5>
                <p class="card-text">Уже сообщал о тараканах в общежитии?
                    Тогда <a href="/login">логинься!</a>
                </p>
                <a href="/register" class="btn btn-primary">Регистрируйся</a>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4 pt-5">
        <div class="card border-0 bg-light">
            <img width="120" class="landing-image" src="/public/images/fill.png" alt="Register">
            <div class="card-body text-center">
                <h5 class="card-title">Оставь заявку</h5>
                <p class="card-text">Заполни форму заявки и обязательно прикрепи фотографию.</p>
                <a href="#" class="btn btn-primary">Пиши нам</a>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-4 pt-5">
        <div class="card border-0 bg-light">
            <img width="120" class="landing-image" src="/public/images/done.png" alt="Register">
            <div class="card-body text-center">
                <h5 class="card-title">Мы поможем</h5>
                <p class="card-text">Мы стараемся обкашливать вопросики быстро.</p>
                <a href="#" class="btn btn-primary">Последние заявки</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="row mt-3">
    <div class="col">
        <counter />
    </div>
</div>
<application-list/>