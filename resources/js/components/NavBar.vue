<template>
    <nav class="navbar navbar-light bg-light fixed-top shadow-sm">
        <div class="container">
            <ul class="nav nav-pills">
                <li
                    v-for="(page, index) in pages"
                    :key="index"
                    class="nav-item"
                >
                    <router-link
                        :to="{name: page.routeName}"
                        class="nav-link"
                        :class="{active: page.routeName === currentRouteName}"
                    >{{ page.text }}</router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>
<script>
export default {
    name: 'NavBar',

    data() {
        return {
            pages: [
                {
                    routeName: 'home',
                    text: 'Адреса',
                },
                {
                    routeName: 'editRoutes',
                    text: 'Маршруты',
                }
            ],
        };
    },

    computed: {
        currentRouteName() {
            return this.$route.name;
        }
    }


}
</script>
