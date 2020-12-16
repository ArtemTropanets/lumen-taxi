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
                        :to="{name: page.pathName}"
                        class="nav-link"
                        :class="{active: page.pathName === currentPathName}"
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
                    pathName: 'home',
                    text: 'Домой',
                },
                {
                    pathName: 'kek',
                    text: 'kek',
                }
            ],
        };
    },

    computed: {
        currentPathName() {
            return this.$route.name;
        }
    }


}
</script>
