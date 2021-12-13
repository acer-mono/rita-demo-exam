const CategoryList = {
    template: `
<div class="container-fluid mt-4">
    <div class="row pr-2">
        <div class=" col-sm-8 col-md-10 pl-0">
            <div class="form-group">
                <input v-model="newItemName" type="text" class="form-control" placeholder="Название новой категории">
            </div>
        </div>
        <div class="col-sm-4 col-md-2">
            <button @click="create" type="submit" class="btn btn-success btn-block">Создать</button>
        </div>
    </div>
    <div class="row">
        <category v-for="item in items" 
        :key="item.id"
        :id="item.id"
        :name="item.name"
        :removeHandler="remove"
        :editHandler="edit"/>
    </div>
</div>
    `,
    data() {
        return {
            newItemName: "",
            items: []
        }
    },
    async mounted() {
        let items = await this.getCategories();
        console.log(items)
        this.items = items;
    },
    methods: {
        async create() {
            let response = await fetch('/categories', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: this.newItemName
                })
            });

            if (response.ok) {
                this.items = await this.getCategories();
            }
        },
        async getCategories() {
            const response = await fetch('/categories', {headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                return await response.json();
            }
            return [];
        },

        async remove(id) {
            const response = await fetch(`/categories/${id}/delete`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                this.items = await this.getCategories();
            }
        },

        async edit(id, newName) {
            const response = await fetch(`/categories/${id}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: JSON.stringify({name: newName})
            });

            if (response.ok) {
                this.items = await this.getCategories();
            }
        },
    },
}
