<template>
    <div>
        <Loader/>
        <SuccessToast/>

        <RouteMapModal/>

        <NavBar/>

        <router-view/>
    </div>
</template>

<script>
import Loader from "./components/Loader";
import NavBar from "./components/NavBar";
import SuccessToast from "./components/SuccessToast";
import RouteMapModal from "./modals/RouteMapModal";

export default {
    components: {
        NavBar,
        SuccessToast,
        Loader,
        RouteMapModal,
    }
}
</script>
