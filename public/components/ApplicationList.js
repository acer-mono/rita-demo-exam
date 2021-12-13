const ApplicationList = {
    template: `
<div class="row justify-content-center">
    <application-item v-for="item in items" 
    :title="item.title"
    :date="item.createdAt"
    :category="item.categoryName"
    :beforeImage="item.photoBefore"
    :afterImage="item.photoAfter" />
</div>
    `,
    data() {
        return {
            items: []
        }
    },
    async mounted() {
        let response = await fetch('/applications/latest', {
            headers: {
                'Accept': 'application/json'
            },
        });

        if (response.ok) {

            let items = await response.json();
            console.log(items);
            this.items = items;
        } else {
            alert("Ты лох!");
        }
    }
}
