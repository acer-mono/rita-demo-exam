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
            <a href="/applications/create" class="ml-2 btn btn-primary btn-lg">Новая заявка</a>
        </div>
    </div>
    <div class="row">
        <account-application v-for="item in this.filterItems" 
        :title="item.title"
        :date="item.date"
        :category="item.category"
        :status="item.status"
        :description="item.description" />
    </div>
</div>
    `,
    data() {
        return {
            newItems: true,
            cancelItems: true,
            doneItems: true,
            items: [
                {
                    title: "Hello",
                    date: "12/06/1992",
                    category: "pups",
                    status: 'Новая',
                    description: 'fgbfgb gbg trbrtbb trht rt trt hrthtr hthrhrt'
                },
                {
                    title: "Hello",
                    date: "12/06/1992",
                    category: "pups",
                    status: 'Решена',
                    description: '/images/fill.png'
                },
                {
                    title: "Hello",
                    date: "12/06/1992",
                    category: "pups",
                    status: 'Отклонена',
                    description: '/images/fill.png'
                },
                {
                    title: "Hello",
                    date: "12/06/1992",
                    category: "pups",
                    status: 'Решена',
                    description: '/images/fill.png'
                }
            ]
        }
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
