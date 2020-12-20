<template>
    <div class="row">
        <div class="col-6">
            <EditRoutesGroup
                title="Вечер"
                type="morning"
                :routes="morningRoutes"
                :noRoutePersonsProp="noRouteMorningPersons"
                @updateNoRoutePersons="updateNoRoutePersons"
                @add-route="addRoute"
                @delete-route="deleteRoute"
            />
        </div>
        <div class="col-6">
            <EditRoutesGroup
                title="Утро"
                type="evening"
                :routes="eveningRoutes"
                :noRoutePersonsProp="noRouteEveningPersons"
                @updateNoRoutePersons="updateNoRoutePersons"
                @add-route="addRoute"
                @delete-route="deleteRoute"
            />
        </div>
    </div>
</template>

<script>
import RouteService from "../services/RouteService";
import EditRoutesGroup from "../components/EditRoutesGroup";
import {capitalizeFirstLetter} from "../services/helpers";

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
        },

        updateNoRoutePersons(newVal, type) {
            this[`noRoute${capitalizeFirstLetter(type)}Persons`] = newVal;
        },

        addRoute(type) {
            this[`${type}Routes`].push({
                persons: [],
            });
        },

        deleteRoute(type, index) {
            this[`${type}Routes`].splice(index, 1);
        }
    },


    created() {
        this.getRoutesForEdit();
    }
}
</script>
