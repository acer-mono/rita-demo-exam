const AccountApplication = {
    template: `
<div class="col-sm-12 col-md-6">
    <div class="card m-2 border-0 text-center" style="max-width: 500px;">
      <div class="card-header">{{category}}</div>
      <div class="card-body">
        <h5 class="card-title">{{title}}</h5>
        <p class="card-text">{{description}}</p>
        <a :href="'/applications/' + id" class="btn btn-primary">Подробнее</a>
      </div>
      <div class="card-footer d-flex justify-content-between">
        <div :class="{'text-success': status == 'Решена', 'text-danger': status == 'Отклонена', 'text-primary': status == 'Новая'}">
        {{status}}
        </div>
        <div class="text-muted">{{date}}</div>
        <div class="text-muted">{{isAdmin}}</div>
      </div>
    </div>
</div>
    `,
    data() {
        return {
        }
    },
    props: ['title', 'date', 'description', 'status', 'category', 'id', 'isAdmin']
}
