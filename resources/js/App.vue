<template>
    <div>
        <Loader/>
        <SuccessToast/>
        <CreateEditPersonModal/>


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
import CreateEditPersonModal from "./modals/CreateEditPersonModal";

export default {
    name: "App",
    components: {
        NavBar,
        SuccessToast,
        Loader,
        CreateEditPersonModal,
    }
}
</script>
