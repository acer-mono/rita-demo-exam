const ApplicationCreationForm = {
    template: `
<div class="col">
   <form class="needs-validation col" novalidate @submit.prevent>
      <div class="form-group">
        <label for="title">Название</label>
        <input
        type="text"
        v-model="title"
        @change="onChange(validators.title)"
        :class="{'form-control': true, 'is-invalid': errors.title}"
        id="name"
        placeholder="Везде мусор, везде снег..."/>
        <div class="invalid-feedback">{{errors.title}}</div>
      </div>
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
        <label for="category">Категория</label>
        <select 
        class="form-control" 
        id="category" 
        @change="onChange(validators.category)" 
        v-model="category">
          <option v-for="item in this.categories" :key="item.id" :value="item.id">{{item.name}}</option>
        </select>
        <div class="invalid-feedback">{{errors.category}}</div>
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
      <button type="submit" v-if="checkForm" @click="createApplication" class="btn btn-primary">Создать</button>
  </form>
</div>
    `,
    data() {
        return {
            categories: [],

            title: "",
            description: "",
            category: "",
            photo: null,

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
                },
                category: {
                    validate: this.checkCategory,
                    errorField: 'category',
                    errorMessage: 'Выберите подходящую категорию'
                }
            }
        }
    },
    async mounted() {
        this.categories = await this.getCategories();
    },
    methods: {
        async getCategories() {
            let response = await fetch('/categories', {
                headers: {
                    'Accept': 'application/json'
                },
            });

            if (response.ok) {
                return await response.json();
            }
            return [];
        },

        checkTitle() {
            return this.title.length != 0;
        },
        checkDescription() {
            return this.description.length != 0;
        },
        checkCategory() {
            return this.category.length != 0;
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
        async createApplication() {
            const formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('categoryId', this.category);
            formData.append('photo', this.photo, this.photo.name);

            let response = await fetch('/applications', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            if(response.ok) {
                window.location = (await response.json()).path + '#categories';
            }
        }
    },
    computed: {
        checkForm() {
            return this.checkTitle()
                && this.checkDescription()
                && this.checkCategory()
                && this.checkPhoto();
        }
    }
}
