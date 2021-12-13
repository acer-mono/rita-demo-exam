const ApplicationInfo = {
    template: `
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Название</div>
        <div class="col-sm-12 col-md-10 pb-2">{{user.title}}</div>
    </div>
    <div class="row">
        <div class="col d-flex justify-content-center pb-2">
            <img :src="user.imageBefore" class="application-image" alt="Проблема">   
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Описание</div>
        <div class="col-sm-12 col-md-10 pb-2">{{user.description}}</div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Категория</div>
        <div class="col-sm-12 col-md-10 pb-2">{{user.category}}</div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Статус</div>
        <div class="col-sm-12 col-md-10 pb-2">{{user.status}}</div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Дата</div>
        <div class="col-sm-12 col-md-10 pb-2">{{user.date}}</div>
    </div>
    <div v-if="user.status==='Новая'" class="row">
        <div class="col pb-2">
         <button class="btn btn-danger">Удалить</button>
        </div>
    </div>
    <div class="row" v-if="user.resolution">
        <div class="col-sm-12 col-md-2 font-weight-bold pb-2">Решение</div>
        <div class="col-sm-12 col-md-10 pb-2">{{user.resolution}}</div>
    </div>
    <div class="row" v-if="user.imageAfter">
        <div class="col d-flex justify-content-center pb-2">
            <img :src="user.imageAfter" class="application-image" alt="Проблема">   
        </div>
    </div>
    <div class="row" v-if="user.status === 'Новая'">
        <div class="col">
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
              <div class="form-group">
                <label for="photo">Фото</label>
                <input 
                type="file" 
                :class="{'is-invalid': errors.photo, 'form-control-file': true}"
                @change="getFile" 
                id="photo" />
                <div class="invalid-feedback">{{errors.photo}}</div> 
              </div>
              <button type="submit" v-if="checkForm" class="btn btn-primary">Решить</button>
          </form>
        </div>
    </div>
</div>
`,
    data() {
        return {
            user: {
                title: "Уберите двор",
                description: "Описание бла-бла-бла",
                category: "Творники-дворники",
                status: "Отклонена",
                date: "12/12/1992",
                resolution: "Я сделаль",
                imageBefore: "/images/done.png",
                imageAfter: "/images/done.png"
            },
            description: "",
            photo: "",
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
    },
    computed: {
        checkForm() {
            return this.checkDescription() && this.checkPhoto();
        }
    }
}
