<template>
    <div>
        <h4 class="text-center mt-1 mb-3">{{ title }}</h4>

        <div v-if="noRoutePersons.length"
             class="pb-1 rounded-2 overflow-hidden"
             style="border: 1px solid red"
        >
            <div
                class="fw-bold text-center bg-light text-danger pt-2 pb-2"
                style="border-bottom: 1px solid lightgrey;"
            >Без маршрута</div>
            <draggable
                tag="table"
                v-model="noRoutePersons"
                v-bind="dragOptions"
                :animation="200"
                ghost-class="moving-person-row"
                class="route-table"
            >
                <tr
                    v-for="person in noRoutePersons"
                    :key="person.id"
                    class="route-table__row"
                >
                    <td class="col-6 p-1">{{ person.name }}</td>
                    <td class="col-6 p-1">{{ person[`${type}_address`] }}</td>
                </tr>
            </draggable>
        </div>

        <transition-group name="fade">
            <div
                v-for="(route, index) in routes"
                :key="index"
                class="mt-2 pb-1 rounded-2"
                style="border: 1px solid #bdbdbd;"
            >
                <div class="container-fluid pt-2 pb-2 bg-light"
                     style="border-bottom: 1px solid lightgrey;
                         border-top-left-radius: inherit;
                         border-top-right-radius: inherit;"
                >
                    <div class="row">
                        <div class="col-4 align-self-center">
                            <vue-timepicker
                                v-model="route.scheduled_at"
                                manual-input
                            ></vue-timepicker>
                        </div>
                        <div class="col-4 align-self-center">
                            <div class="fw-bold text-center">Маршрут {{ index + 1 }}</div>
                        </div>
                        <div class="col-4 align-self-center text-end">
                            <button
                                v-show="route.persons.length"
                                class="btn btn-sm btn-primary"
                                style="width: 70px;"
                                @click="showRouteMap(route)"
                            >Карта</button>
                            <button
                                v-show="!route.persons.length"
                                class="btn btn-sm btn-danger"
                                style="width: 70px;"
                                @click="$emit('delete-route', type, index)"
                            >Удалить</button>
                        </div>
                    </div>
                </div>
                <draggable
                    tag="table"
                    v-model="route.persons"
                    v-bind="dragOptions"
                    :animation="200"
                    ghost-class="moving-person-row"
                    class="route-table"
                    style="position: relative; z-index: 1"
                >
                    <tr
                        v-for="person in route.persons"
                        :key="person.id"
                        class="route-table__row"
                    >
                        <td class="col-6 p-1">{{ person.name }}</td>
                        <td class="col-6 p-1">{{ person[`${type}_address`] }}</td>
                    </tr>
                </draggable>
            </div>
        </transition-group>


        <div class="d-flex justify-content-center mt-2">
            <button
                class="btn btn-sm btn-primary"
                @click="$emit('add-route', type)"
            >Добавить маршрут</button>
        </div>

    </div>
</template>
<script>
import draggable from 'vuedraggable';
import VueTimepicker from 'vue2-timepicker/src/vue-timepicker';

export default {
    name: 'EditRoutesGroup',


    components: {
        draggable,
        VueTimepicker,
    },


    props: {
        title: String,
        type: {
            type: String,
            validator(val) {
                return ['evening', 'morning'].includes(val);
            }
        },
        routes: Array,
        noRoutePersonsProp: Array,
    },


    data() {
        return {
            dragOptions: {
                group: this.type,
            }
        }
    },


    computed: {
        noRoutePersons: {
            get() {
                return this.noRoutePersonsProp;
            },
            set(newVal) {
                this.$emit('updateNoRoutePersons', newVal, this.type);
            }
        },
    },


    methods: {
        showRouteMap(route) {
            const addresses = route.persons.map(person => {
                return person[`${this.type}_address`]
            });

            this.$eventBus.$emit('show-route-map-modal', addresses, this.type);
        },
    },
}
</script>

<style lang="scss" scoped>
.route-table {
    width: 100%;
    min-height: 30px;
    margin-top: -1px;

    &__row {
        cursor: move;

        &:not(:last-child) td {
            border-top: 1px solid lightgrey;
            border-bottom: 1px solid lightgrey;
        }
    }
}

.moving-person-row {
    background: #F7FAFC;
    outline: 1px solid #4299e1;
}

.fade-enter-active {
    transition: opacity .5s;
}
.fade-enter {
    opacity: 0;
}
</style>
