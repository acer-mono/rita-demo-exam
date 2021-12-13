</div>
<footer class="page-footer font-small blue">
    <div class="footer-copyright text-center py-3">© 2021 Copyright:
        <a href="https://github.com/acer-mono"> Ivanova M.V.</a>
    </div>
</footer>
<script src="/public/js/vue@2.6.js"></script>
<script src="/public/components/ApplicationItem.js"></script>
<script src="/public/components/AccountApplication.js"></script>
<script src="/public/components/ApplicationList.js"></script>
<script src="/public/components/AccountApplications.js"></script>
<script src="/public/components/AccountInfo.js"></script>
<script src="/public/components/Category.js"></script>
<script src="/public/components/CategoryList.js"></script>
<script src="/public/components/Counter.js"></script>
<script src="/public/components/ApplicationCreationForm.js"></script>
<script src="/public/components/ApplicationInfo.js"></script>
<script src="/public/components/RegistrationPage.vue.js"></script>
<script src="/public/components/LoginPage.vue.js"></script>
<script>
    Vue.component("AccountInfo", AccountInfo);
    Vue.component("Category", Category);
    Vue.component("CategoryList", CategoryList);
    Vue.component("AccountApplication", AccountApplication);
    Vue.component("AccountApplications", AccountApplications);
    Vue.component("ApplicationItem", ApplicationItem);
    Vue.component("ApplicationList", ApplicationList);
    Vue.component("Counter", Counter);
    Vue.component("ApplicationCreationForm", ApplicationCreationForm);
    Vue.component("ApplicationInfo", ApplicationInfo);
    Vue.component("RegistrationPage", RegistrationPage);
    Vue.component("LoginPage", LoginPage);
    const app = new Vue({
        el: "#app"});
</script>
</body