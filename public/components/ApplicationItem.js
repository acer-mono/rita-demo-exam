const ApplicationItem = {
    template: `
<div class="col-sm-12 col-md-6">
    <div class="card m-2 border-0" style="max-width: 500px;">
      <div class="row no-gutters">
        <div class="col-4" @mouseover="isBefore = false" @mouseleave="isBefore = true">
        <img v-if="isBefore" :src="'/uploads/' + beforeImage" class="card-img" alt="before">
        <img v-if="!isBefore" :src="'/uploads/' + afterImage" class="card-img card-img-scaled" alt="after">
        </div>
        <div class="col-8">
          <div class="card-body">
            <h5 class="card-title">{{title}}</h5>
            <p class="card-text">{{category}}</p>
            <p class="card-text"><small class="text-muted">{{date}}</small></p>
          </div>
        </div>
      </div>
    </div>
</div>
    `,
    data() {
        return {
            isBefore: true
        }
    },
    props: ['title', 'date', 'beforeImage', 'afterImage', 'category']
}
