const RegistrationPage = {
    template: `
    <div class="container form-field-width">
        <div class="row">
            <form class="needs-validation col" novalidate action="/register" method="POST">
              <div class="form-group">
                <label for="name">Имя</label>
                <input
                type="text"
                name="name"
                v-model="name"
                @change="onChange(validators.name)"
                :class="{'form-control': true, 'is-invalid': errors.name}"
                id="name"
                placeholder="Иванов Иван Иванович"/>
                <div class="invalid-feedback">{{errors.name}}</div>
              </div>
              <div class="form-group">
                <label for="login">Логин</label>
                <input
                type="text"
                name="login"
                v-model="login"
                @change="onChange(validators.login)"
                :class="{'form-control': true, 'is-invalid': errors.login}"
                id="login"
                placeholder="Iwanow"/>
                <div class="invalid-feedback">{{errors.login}}</div>
              </div>
              <div class="form-group">
                <label for="email">Адрес электронной почты</label>
                <input type="email"
                name="email"
                v-model="email"
                @change="onChange(validators.email)"
                :class="{'form-control': true, 'is-invalid': errors.email}"
                id="email"
                placeholder="iwanow@gmail.com"/>
                <div class="invalid-feedback">{{errors.email}}</div>
              </div>
              <div class="form-group">
                <label for="password">Пароль</label>
                <input v-model="password"
                name="password"
                @change="onChange(validators.password)"
                type="password"
                id="password"
                :class="{'form-control': true, 'is-invalid': errors.password}"
                placeholder="Пароль"/>
                <div class="invalid-feedback">{{errors.password}}</div>
              </div>
              <div class="form-group">
                <label for="confirmPassword">Подтверждение пароля</label>
                <input
                v-model="passwordConfirmation"
                @change="onChange(validators.confirm)"
                :class="{'form-control': true, 'is-invalid': errors.confirm}"
                type="password"
                class="form-control"
                id="confirmPassword"
                placeholder="Повтор пароля" />
                <div class="invalid-feedback">{{errors.confirm}}</div>
              </div>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="consent" v-model="consent">
                <label class="form-check-label" for="consent">Согласие на обработку персональных данных</label>
              </div>
              <button type="submit" v-if="checkForm" class="btn btn-primary">Регистрация</button>
            </form>
        </div>
    </div>
    `,
    data() {
        return {
            name: "",
            email: "",
            password: "",
            passwordConfirmation: "",
            consent: true,
            login: "",
            errors: {
                name: "",
                password: "",
                confirm: "",
                email: "",
                server: "",
                login: ""
            },
            validators: {
                email: {
                    validate: this.checkEmail,
                    errorField: 'email',
                    errorMessage: 'Некорректный email'
                },
                name: {
                    validate: this.checkName,
                    errorField: 'name',
                    errorMessage: 'Неверное имя'
                },
                password: {
                    validate: this.checkPassword,
                    errorField: 'password',
                    errorMessage: 'Длина пароля должна составлять не менее 8 символов'
                },
                confirm: {
                    validate: this.checkConfirm,
                    errorField: 'confirm',
                    errorMessage: 'Пароли не совпадают'
                },
                login: {
                    validate: this.checkLogin,
                    errorField: 'login',
                    errorMessage: 'Логин может содержать только символы латинского алфавита'
                }
            }
        }
    },
    methods: {
        checkPassword() {
            return this.password.length >= 8;
        },

        checkConfirm() {
            return this.password === this.passwordConfirmation;
        },

        checkEmail() {
            if (this.email === "") {
                return false;
            }
            const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(this.email);
        },

        checkLogin() {
            if (this.login === "") {
                return false;
            }
            const re = /^[a-zA-Z]+/;
            return re.test(this.login);
        },

        checkName() {
            if (this.name === "") {
                return false;
            }
            const re = /^[а-яА-ЯёЁ\s-]+$/;
            return re.test(this.name);

        },

        onChange({validate, errorField, errorMessage}) {
            if(validate()) {
                this.errors[`${errorField}`] = "";
                return;
            }
            this.errors[`${errorField}`] = errorMessage;
        },
    },

    computed: {
        checkForm() {
            return this.checkName()
                && this.checkLogin()
                && this.checkEmail()
                && this.checkPassword()
                && this.checkConfirm()
                && this.consent;
        }
    }
}
