const LoginPage = {
    template: `
    <div class="container form-field-width">
        <div class="row">
            <form class="needs-validation col" action="/login" method="POST" novalidate>
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
                <label for="password">Пароль</label>
                <input v-model="password"
                @change="onChange(validators.password)"
                type="password"
                name="password"
                id="password"
                :class="{'form-control': true, 'is-invalid': errors.password}"
                placeholder="Пароль"/>
                <div class="invalid-feedback">{{errors.password}}</div>
              </div>
              <button type="submit" v-if="checkForm" class="btn btn-primary">Вход</button>
            </form>
        </div>
    </div>
    `,
    data() {
        return {
            password: "",
            login: "",
            errors: {
                password: "",
                login: ""
            },
            validators: {
                password: {
                    validate: this.checkPassword,
                    errorField: 'password',
                    errorMessage: 'Длина пароля должна составлять не менее 8 символов'
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

        checkLogin() {
            if (this.login === "") {
                return false;
            }
            const re = /^[a-zA-Z]+/;
            return re.test(this.login);
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
            return this.checkLogin()
                && this.checkPassword()
        }
    }
}
