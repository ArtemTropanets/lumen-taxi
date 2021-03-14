<template>
    <nav class="navbar navbar-light bg-light fixed-top shadow-sm">
        <div class="container d-flex justify-content-between">
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

            <div style="margin-left: -75px;">
                <a
                    href="https://www.google.com.ua/maps/"
                    class="btn btn-secondary"
                    target="_blank"
                    style="width: 160px;"
                >Google Maps</a>
                <a
                    href="https://2gis.ua/odessa"
                    class="btn btn-secondary"
                    target="_blank"
                    style="width: 160px"
                >2GIS</a>
            </div>

            <ul class="nav nav-pills">
                <li>
                    <router-link
                        :to="{name: 'persons'}"
                        class="nav-link"
                        :class="{active: 'persons' === currentRouteName}"
                    >Сотрудники</router-link>
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
                },
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
