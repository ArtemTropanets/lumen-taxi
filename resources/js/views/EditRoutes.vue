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
    components: {
        EditRoutesGroup,
    },


    props: {
        routesProp: {
            type: Object,
            required: true,
        },
    },


    data() {
        return {
            eveningRoutes: this.routesProp.evening_routes,
            morningRoutes: this.routesProp.morning_routes,
            noRouteEveningPersons: this.routesProp.no_route_evening_persons,
            noRouteMorningPersons: this.routesProp.no_route_morning_persons,
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

                    this.setRoutesForCompare();
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

        setRoutesForCompare() {
            this.initialRoutes = {
                evening: _.cloneDeep(this.eveningRoutes),
                morning: _.cloneDeep(this.morningRoutes),
            }

            this.normalizeTimeInRoutes(this.initialRoutes.evening);
            this.normalizeTimeInRoutes(this.initialRoutes.morning);

            this.routesAfterChange = {
                evening: this.eveningRoutes,
                morning: this.morningRoutes,
            };

            this.normalizeTimeInRoutes(this.routesAfterChange.evening);
            this.normalizeTimeInRoutes(this.routesAfterChange.morning);
        },

        normalizeTimeInRoutes(routes) {
            routes.forEach(route => route.scheduled_at = route.scheduled_at.slice(0, 5));
        },


        areRoutesChanged() {
            return !_.isEqual(
                JSON.parse(JSON.stringify(this.initialRoutes)),
                JSON.parse(JSON.stringify(this.routesAfterChange))
            );
        },


        beforeUnloadHandler(event) {
            if (this.areRoutesChanged()) {
                event.preventDefault();
                // Хром требует установки возвратного значения.
                event.returnValue = '1';
            }
        },
    },


    created() {
        this.setRoutesForCompare();
        window.addEventListener('beforeunload', this.beforeUnloadHandler);
    },


    destroyed() {
        window.removeEventListener('beforeunload', this.beforeUnloadHandler);
    },


    beforeRouteLeave(to, from, next) {
        let confirm = true;

        if (this.areRoutesChanged()) {
            confirm = window.confirm('Ты точно хочешь перейти на другую страницу,не сохранив маршруты? Изменения будут утеряны.');
        }

        next(confirm);
    },
}
</script>
