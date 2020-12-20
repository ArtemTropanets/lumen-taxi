<template>
    <div class="row">
        <div class="col-6">
            <EditRoutesGroup
                title="Вечер"
            />
        </div>
        <div class="col-6">
            <EditRoutesGroup
                title="Утро"
            />
        </div>
    </div>
</template>

<script>
import RouteService from "../services/RouteService";
import EditRoutesGroup from "../components/EditRoutesGroup";

export default {
    name: "EditRoutes",
    components: {EditRoutesGroup},
    data() {
        return {
            morningRoutes: [],
            eveningRoutes: [],
            noRouteMorningPersons: [],
            noRouteEveningPersons: [],
        };
    },


    methods: {
        getRoutesForEdit() {
            this.$eventBus.$emit('show-loader');
            RouteService.getRoutesForEdit()
                .then(response => {
                    this.morningRoutes = response.data.morning_routes;
                    this.eveningRoutes = response.data.evening_routes;
                    this.noRouteMorningPersons = response.data.no_route_morning_persons;
                    this.noRouteEveningPersons = response.data.no_route_evening_persons;
                })
                .catch(error => {
                    console.dir(error);
                    alert('Error');
                })
                .finally(() => this.$eventBus.$emit('show-loader', false));
        }
    },


    created() {
        this.getRoutesForEdit();
    }
}
</script>
