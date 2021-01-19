<template>
    <div>
        <div class="d-flex justify-content-center position-relative">
            <button
                class="btn btn-success position-absolute"
                style="top: 5px;"
                @click="saveRoutes"
            >Сохранить маршруты</button>
        </div>
        <div class="row">
            <div class="col-6">
                <EditRoutesGroup
                    title="Вечер"
                    type="evening"
                    :routes="eveningRoutes"
                    :noRoutePersonsProp="noRouteEveningPersons"
                    @updateNoRoutePersons="updateNoRoutePersons"
                    @add-route="addRoute"
                    @delete-route="deleteRoute"
                />
            </div>
            <div class="col-6">
                <EditRoutesGroup
                    title="Утро"
                    type="morning"
                    :routes="morningRoutes"
                    :noRoutePersonsProp="noRouteMorningPersons"
                    @updateNoRoutePersons="updateNoRoutePersons"
                    @add-route="addRoute"
                    @delete-route="deleteRoute"
                />
            </div>
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
            eveningRoutes: [],
            morningRoutes: [],
            noRouteEveningPersons: [],
            noRouteMorningPersons: [],
        };
    },


    methods: {
        getRoutesForEdit() {
            this.$eventBus.$emit('show-loader');
            RouteService.getRoutesForEdit()
                .then(response => {
                    this.eveningRoutes = response.data.evening_routes;
                    this.morningRoutes = response.data.morning_routes;
                    this.noRouteEveningPersons = response.data.no_route_evening_persons;
                    this.noRouteMorningPersons = response.data.no_route_morning_persons;
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
                type: (type === 'evening') ? 'evening' : 'morning',
                scheduled_at: (type === 'evening') ? '18:10' : '08:30',
                persons: [],
            });
        },

        deleteRoute(type, index) {
            this[`${type}Routes`].splice(index, 1);
        },

        saveRoutes() {
            this.$eventBus.$emit('show-loader');
            RouteService.saveRoutes({
                evening_routes: this.eveningRoutes,
                morning_routes: this.morningRoutes,
            })
                .then(response => {
                    if (response.data.status !== 'ok') {
                        throw new Error('Unexpected response');
                    }

                    this.$eventBus.$emit('show-success-toast');
                })
                .catch(error => {
                    console.dir(error);
                    alert('Error');
                })
                .finally(() => {
                    this.$eventBus.$emit('show-loader', false);
                    this.getRoutesForEdit();
                });
        },
    },


    created() {
        this.getRoutesForEdit();
    }
}
</script>
