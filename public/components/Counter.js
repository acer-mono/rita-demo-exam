const Counter = {
    template: `<h3 class="text-center">Всего обработано: {{count}} заявок</h3>`,
    data() {
        return {
            previous: 0,
            count: 0,
            audio: new Audio('/public/audio/notif.mp3'),
        }
    },
    methods: {
        async update() {
            const result = await this.fetchCount();
            if (this.previous !== result) {
                this.previous = this.count;
                this.count = result;
                this.audio.play();
            }
        },

        async fetchCount() {
            let response = await fetch('/applications/total', {
                headers: {
                    'Accept': 'application/json'
                },
            });

            if (response.ok) {
                return (await response.json()).total;
            } else {
                return this.count;
            }
        }
    },
    mounted: function() {
        this.update();
        setInterval(this.update, 5000);
    }
}
