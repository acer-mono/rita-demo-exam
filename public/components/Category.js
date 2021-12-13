const Category = {
    template: `
<div class="col-12 pt-2" :key="id">
    <div class="row bg-white p-2">
        <div class="col-sm-8 col-md-10 d-flex align-items-center">
            <span v-if="!isEdit" class="input-group-text border-0 bg-white">{{itemName}}</span>
            <input v-if="isEdit" type="text" class="form-control" id="basic-url" v-model="itemName" />
        </div>
        <div class="col-sm-4 col-md-2">
           <button type="button" class="btn btn-primary btn-block" @click="edit(id)">{{ isEdit ? "Сохранить" : "Изменить"}}</button>
           <button type="button" class="btn btn-danger btn-block" @click="deleteItem()">Удалить</button>
        </div>
    </div>    
</div>
    `,
    data() {
        return {
            isEdit: false,
            itemName: this.name
        }
    },

    props: ['name', 'id', 'removeHandler', 'editHandler'],
    methods: {
        async deleteItem() {
            if(confirm("Вы уверены, что хотите удалить эту категорию?")) {
                await this.removeHandler(this.id);
            }
        },
        edit() {
            if(this.isEdit) {
                this.editHandler(this.id, this.itemName);
            } else {
                //todo
            }
            this.isEdit = !this.isEdit;
        }
    }
}
