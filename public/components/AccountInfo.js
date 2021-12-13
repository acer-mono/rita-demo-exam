const AccountInfo = {
    template: `
<div class="row">
    <div class="col mt-3">
    <table class="table table-striped">
      <tbody>
        <tr>
          <td>ФИО</td>
          <td>{{this.user.name}}</td>
        </tr>
        <tr>
          <td>e-mail</td>
          <td>{{this.user.email}}</td>
        </tr>
        <tr>
          <td>Логин</td>
          <td>{{this.user.login}}</td>
        </tr>
      </tbody>
    </table>
    </div>
</div>
`,
    data() {
        return {
            user: {
                name: "",
                login: "",
                email: ""
            }
        }
    },
    async mounted() {
        let response = await fetch('/account', {
            headers: {
                'Accept': 'application/json'
            },
        });

        if (response.ok) {
            this.user = await response.json();
        }
    }
}
