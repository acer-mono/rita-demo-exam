const ApplicationInfo = {
    template: `
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Название</div>
        <div class="col-sm-12 col-md-10 pb-2">{{application.title}}</div>
    </div>
    <div class="row">
        <div class="col d-flex justify-content-center pb-2">
            <img :src="'/uploads/' + application.photoBefore" class="application-image" alt="Проблема">   
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Описание</div>
        <div class="col-sm-12 col-md-10 pb-2">{{application.description}}</div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Категория</div>
        <div class="col-sm-12 col-md-10 pb-2">{{application.categoryName}}</div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Статус</div>
        <div class="col-sm-12 col-md-10 pb-2">{{application.status}}</div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Дата</div>
        <div class="col-sm-12 col-md-10 pb-2">{{application.createdAt}}</div>
    </div>
    <div v-if="application.status==='Новая' && !isAdmin" class="row">
        <div class="col pb-2">
         <button @click="remove" class="btn btn-danger">Удалить</button>
        </div>
    </div>
    <div class="row" v-if="application.resolution">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Решение</div>
        <div class="col-sm-12 col-md-10 pb-2">{{application.resolution}}</div>
    </div>
    <div class="row" v-if="application.photoAfter">
        <div class="col d-flex justify-content-center pb-2">
            <img :src="'/uploads/'+application.photoAfter" class="application-image" alt="Проблема">   
        </div>
    </div>
    <div class="row" v-if="application.status === 'Новая' && isAdmin">
        <div class="col">
        <h3 class="text-center">Решение</h3>
            <form class="needs-validation col" novalidate @submit.prevent>
              <div class="form-group">
                <label for="description">Описание</label>
                <textarea 
                class="form-control" 
                id="description" 
                v-model="description"
                @change="onChange(validators.description)"
                :class="{'form-control': true, 'is-invalid': errors.description}"
                rows="3"></textarea>
                <div class="invalid-feedback">{{errors.description}}</div>
              </div>
              <div class="form-group">
                <label for="status">Статус</label>
                <select 
                class="form-control" 
                id="status" 
                v-model="status">
                  <option selected value="Отклонена">Отклонена</option>
                  <option value="Решена">Решена</option>
                </select>
              </div>
              <div v-if="status === 'Решена'" class="form-group">
                <label for="photo">Фото</label>
                <input 
                type="file" 
                :class="{'is-invalid': errors.photo, 'form-control-file': true}"
                @change="getFile" 
                id="photo" />
                <div class="invalid-feedback">{{errors.photo}}</div> 
              </div>
              <button type="submit" v-if="checkForm" @click="decide" class="btn btn-primary">Решить</button>
          </form>
        </div>
    </div>
</div>
`,
    data() {
        return {
            application: {
                id: "",
                title: "",
                description: "",
                categoryName: "",
                status: "",
                createdAt: "",
                resolution: "",
                photoBefore: "",
                photoAfter: ""
            },
            description: "",
            photo: null,
            status: "Отклонена",
            errors: {
                title: "",
                description: "",
                category: "",
                photo: ""
            },
            validators: {
                title: {
                    validate: this.checkTitle,
                    errorField: 'title',
                    errorMessage: 'Поле не может быть пустым'
                },
                description: {
                    validate: this.checkDescription,
                    errorField: 'description',
                    errorMessage: 'Поле не может быть пустым'
                }
            }
        }
    },
    props: ['applicationInfo', 'isAdmin'],
    mounted() {
        const status = ['Новая', 'Решена', 'Отклонена'];
        this.application = JSON.parse(this.applicationInfo);
        this.application.status = status[this.application.status];
        console.log(this.application)
    },
    methods: {
        checkDescription() {
            return this.description.length != 0;
        },
        checkPhoto() {
            return this.photo && !this.errors.photo;
        },
        onChange({validate, errorField, errorMessage}) {
            if(validate()) {
                this.errors[`${errorField}`] = "";
                return;
            }
            this.errors[`${errorField}`] = errorMessage;
        },
        getFile (e) {
            const types = ["image/jpeg", "image/png", "image/pjpeg", "image/png" , "image/vnd.wap.wbmp"]
            const maxSize = 10000000;
            const file = e.target.files[0];

            if(file.size > maxSize) {
                this.errors.photo = "Размер фото не может превышать 10Мб";
                return;
            }

            if(!types.includes(file.type)) {
                this.errors.photo = "Неподходящий формат. Для загрузки доступны файлы формата jpg, jpeg, png, bmp";
                return;
            }

            this.errors.photo = "";
            this.photo = file;
        },
        async remove() {
            const response = await fetch(`/applications/${this.application.id}/delete`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                window.location = '/account#applications';
            } else {
                console.log('Не удалсь удалить заявку, попробуйте еще раз')
            }
        },
        async decide() {
            if (this.status === 'Решена') {
                await this.resolve();
            } else {
                await this.reject();
            }
        },
        async reject() {
            const formData = new FormData();
            formData.append('resolution', this.description);

            let response = await fetch(`/applications/${this.application.id}/reject`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            if(response.ok) {
                window.location = window.location;
            }
        },
        async resolve() {
            const formData = new FormData();
            formData.append('resolution', this.description);
            formData.append('photo', this.photo, this.photo.name);

            let response = await fetch(`/applications/${this.application.id}/resolve`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            if(response.ok) {
                window.location = window.location;
            }
        },
    },
    computed: {
        checkForm() {
            return this.checkDescription()
                && (this.status === 'Отклонена'
                    || this.status === 'Решена' && this.checkPhoto()
                );
        }
    }
}
