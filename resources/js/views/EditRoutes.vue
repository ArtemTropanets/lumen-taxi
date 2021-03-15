<template>
    <div class="container">
        <div class="position-fixed pt-2 shadow-sm"
             style="top: 56px; right: 0; left: 0; height: 45px; background-color: white; z-index: 5"
        >
            <div class="container">
                <div class="row">
                    <div class="col-6 position-relative">
                        <h4
                            v-show="!printScreenMode"
                            class="text-center"
                        >Вечер</h4>
                        <button
                            v-show="printScreenMode"
                            class="position-absolute end-50 btn btn-primary"
                            style="transform: translateX(50%)"
                            @click="copyRoutesImgToClipboard('evening')"
                        >Сделать скриншот</button>
                    </div>
                    <div class="col-6 position-relative">
                        <div class="float-end">
                            <div class="form-check form-switch cursor-pointer">
                                <input
                                    v-model="printScreenMode"
                                    class="form-check-input cursor-pointer" type="checkbox" id="flexSwitchCheckDefault"
                                >
                                <label class="form-check-label cursor-pointer" for="flexSwitchCheckDefault">Режим PrintScreen</label>
                            </div>
                        </div>
                        <h4
                            v-show="!printScreenMode"
                            class="position-absolute end-50"
                            style="transform: translateX(50%)"
                        >Утро</h4>
                        <button
                            v-show="printScreenMode"
                            class="position-absolute end-50 btn btn-primary"
                            style="transform: translateX(50%)"
                            @click="copyRoutesImgToClipboard('morning')"
                        >Сделать скриншот</button>
                    </div>
                </div>
            </div>

            <button
                v-show="!printScreenMode"
                class="btn btn-success position-absolute"
                style="top: 5px; left: 50%; transform: translateX(-50%)"
                @click="saveRoutes"
            >Сохранить маршруты</button>
        </div>
        <div class="row">
            <div class="col-6">
                <EditRoutesGroup
                    title="Вечер"
                    type="evening"
                    :print-screen-mode="printScreenMode"
                    :routes="eveningRoutes"
                    :noRoutePersonsProp="noRouteEveningPersons"
                    @updateNoRoutePersons="updateNoRoutePersons"
                    @append-new-route="appendNewRoute"
                    @prepend-new-route="prependNewRoute"
                    @delete-route="deleteRoute"
                />
            </div>
            <div class="col-6">
                <EditRoutesGroup
                    title="Утро"
                    type="morning"
                    :print-screen-mode="printScreenMode"
                    :routes="morningRoutes"
                    :noRoutePersonsProp="noRouteMorningPersons"
                    @updateNoRoutePersons="updateNoRoutePersons"
                    @append-new-route="appendNewRoute"
                    @prepend-new-route="prependNewRoute"
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
import html2canvas from "html2canvas";


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
            printScreenMode: false,
            eveningRoutes: this.routesProp.evening_routes,
            morningRoutes: this.routesProp.morning_routes,
            noRouteEveningPersons: this.routesProp.no_route_evening_persons,
            noRouteMorningPersons: this.routesProp.no_route_morning_persons,
        };
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

        createRoute(type) {
            return {
                id: Date.now(),
                type: (type === 'evening') ? 'evening' : 'morning',
                scheduled_at: (type === 'evening') ? '18:10' : '08:30',
                persons: [],
            };
        },

        appendNewRoute(type) {
            this[`${type}Routes`].push(
                this.createRoute(type)
            );
        },

        prependNewRoute(type) {
            this[`${type}Routes`].unshift(
                this.createRoute(type)
            );
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

        copyRoutesImgToClipboard(type) {
            this.$eventBus.$emit('show-loader');
            setTimeout(() => {
                try {
                    html2canvas(document.getElementById(`capture_${type}`)).then(canvas => {
                        canvas.toBlob((blob) => {
                            const item = new ClipboardItem({ "image/png": blob });
                            navigator.clipboard.write([item])
                                .then(() => {
                                    this.$eventBus.$emit('show-success-toast');
                                })
                                .catch(error => {
                                    console.dir(error);
                                    alert('Error');
                                })
                                .finally(() => this.$eventBus.$emit('show-loader', false));
                        });
                    });
                } catch {
                    this.$eventBus.$emit('show-loader', false);
                }
            }, 100);
        },
    },
}
</script>
