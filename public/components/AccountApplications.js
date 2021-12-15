const AccountApplications = {
    template: `
<div class="container">
    <div class="row justify-content-center pt-3">
        <div class="col-md-6 col-sm-12">
            <div class="form-check ml-2 pb-2">
              <input class="form-check-input" type="checkbox" v-model="newItems" id="check1">
              <label class="form-check-label" for="check1">
                Новые
              </label>
            </div>
            <div class="form-check ml-2 pb-2">
              <input class="form-check-input" type="checkbox" v-model="cancelItems" id="check2">
              <label class="form-check-label" for="check2">
                Отклоненные
              </label>
            </div>
            <div class="form-check ml-2 pb-2">
              <input class="form-check-input" type="checkbox" v-model="doneItems" id="check3">
              <label class="form-check-label" for="check3">
                Решенные
              </label>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 d-flex align-items-center pb-2">
            <a v-if="!this.isAdmin" href="/applications/create" class="ml-2 btn btn-primary btn-lg">Новая заявка</a>
        </div>
    </div>
    <div class="row">
        <account-application v-for="item in this.filterItems" 
        :key="item.id"
        :id="item.id"
        :title="item.title"
        :date="item.updatedAt"
        :category="item.categoryName"
        :status="item.status"
        :description="item.description"
        :isAdmin="this.isAdmin"/>
    </div>
</div>
    `,
    data() {
        return {
            status: ['Новая', 'Решена', 'Отклонена'],
            newItems: true,
            cancelItems: true,
            doneItems: true,
            items: []
        }
    },
    props: ['isAdmin'],
    methods: {
        async fetchApplications() {
            let response = await fetch('/applications', {
                headers: {
                    'Accept': 'application/json'
                },
            });

            if (response.ok) {
                this.items = await response.json();
                this.items.map(el => el.status = this.status[el.status]);
            } else {
                alert("Не удалось загрузить данные, обновите страницу");
            }
        }
    },
    async mounted() {
        console.log(this.isAdmin);
        await this.fetchApplications();
    },
    computed: {
        filterItems() {
            return this.items.filter(el => {
                if(this.newItems && el.status === 'Новая') {
                    return true;
                }
                if(this.cancelItems && el.status === 'Отклонена') {
                    return true;
                }
                if(this.doneItems && el.status === 'Решена') {
                    return true;
                }
                return false;
            })
        }
    }
}
