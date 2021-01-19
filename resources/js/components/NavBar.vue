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

            <ul class="nav nav-pills">
                <li>
                    <router-link
                        :to="{name: 'allPersons'}"
                        class="nav-link"
                        :class="{active: 'allPersons' === currentRouteName}"
                    >Сотрудники</router-link>
                </li>
            </ul>

<!--            <button-->
<!--                class="btn btn-primary"-->
<!--                @click="$eventBus.$emit('show-create-edit-person-modal', 'create')"-->
<!--            >Добавить сотрудника</button>-->
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
