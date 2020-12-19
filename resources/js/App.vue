<template>
    <div>
        <Loader/>
        <SuccessToast/>


        <NavBar/>
        <div class="container">
            <router-view/>
        </div>
    </div>
</template>

<script>
import Loader from "./components/Loader";
import NavBar from "./components/NavBar";
import SuccessToast from "./components/SuccessToast";

export default {
    name: "App",
    components: {SuccessToast, NavBar, Loader}
}
</script>
