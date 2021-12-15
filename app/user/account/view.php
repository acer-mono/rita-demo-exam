<div class="row">
    <div class="col">
        <h3 class="text-center">Личный кабинет <?php if (Session::getInstance()->isAdmin()): ?>администратора<?php endif; ?></h3>
    </div>
</div>
<div class="row">
    <div class="col">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="home" aria-selected="true">Профиль</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="applications-tab" data-toggle="tab" href="#applications" role="tab" aria-controls="applications" aria-selected="false">Заявки</a>
            </li>
            <?php if (Session::getInstance()->isAdmin()): ?>
            <li class="nav-item">
                <a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories" role="tab" aria-controls="categories" aria-selected="false">Категории</a>
            </li>
            <?php endif; ?>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active"
                 id="profile"
                 role="tabpanel"
                 aria-labelledby="home-tab">
                <account-info />
            </div>
            <div class="tab-pane fade"
                 id="applications"
                 role="tabpanel"
                 aria-labelledby="profile-tab">
                <account-applications />
            </div>
            <?php if (Session::getInstance()->isAdmin()): ?>
            <div class="tab-pane fade"
                 id="categories"
                 role="tabpanel"
                 aria-labelledby="profile-tab">
                <category-list />
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', () => {
        let id = location.hash.replace('#', '');
        let element = document.getElementById(id);

        if (element === null) {
            return;
        }

        document.getElementById('profile-tab').classList.remove('active');
        document.getElementById('profile').classList.remove('show', 'active');
        document.getElementById(`${id}-tab`).classList.add('active');
        element.classList.add('show', 'active');
    });
</script>
