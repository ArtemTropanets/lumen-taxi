<template>
    <div>
        <Loader/>

        <NavBar/>

        <div class="container">
            <router-view/>
        </div>
    </div>
</template>

<script>
import Loader from "./components/Loader";
import NavBar from "./components/NavBar";

export default {
    name: "App",
    components: {NavBar, Loader}
}
</script>
