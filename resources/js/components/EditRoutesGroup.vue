<template>
    <div class="mt-5">
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
                    <td class="p-1">
                        <CopyBtn
                            :phone="person.phone"
                            :tooltip-html="person.phone ? `Скопировать <div>номер</div> <div style='max-width: 95px'>${person.phone}</div>` : 'Номер <div> не сохранен</div>'"
                            position="left"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-teletext-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                            </svg>
                        </CopyBtn>
                    </td>
                    <td class="col-5 p-1">{{ person.name }}</td>
                    <td class="col-7 p-1">
                        <OpenMapLinkBtns :address="person[`${type}_address`]"/>
                        <CopyBtn
                            :text="person[`${type}_address`]"
                            tooltip-html="Скопировать адрес"
                            position="right"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                            </svg>
                        </CopyBtn>
                        {{ person[`${type}_address`] }}
                    </td>
                </tr>
            </draggable>
        </div>

        <div
            v-if="routes.length"
            class="d-flex justify-content-center mt-2"
        >
            <button
                class="btn btn-sm btn-primary"
                @click="$emit('prepend-new-route', type)"
            >Добавить маршрут</button>
        </div>

        <transition-group name="fade">
            <div
                v-for="(route, index) in routes"
                :key="route.id"
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
                        <td class="p-1">
                            <CopyBtn
                                :text="person.phone"
                                :tooltip-html="person.phone ? `Скопировать <div>номер</div> <div style='max-width: 95px'>${person.phone}</div>` : 'Номер <div> не сохранен</div>'"
                                position="left"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-teletext-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
                                </svg>
                            </CopyBtn>
                        </td>
                        <td class="col-5 p-1">{{ person.name }}</td>
                        <td class="col-7 p-1">
                            <OpenMapLinkBtns :address="person[`${type}_address`]"/>
                            <CopyBtn
                                :text="person[`${type}_address`]"
                                tooltip-html="Скопировать адрес"
                                position="right"
                                class="float-end"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                </svg>
                            </CopyBtn>
                            {{ person[`${type}_address`] }}
                        </td>
                    </tr>
                </draggable>
            </div>
        </transition-group>


        <div class="d-flex justify-content-center mt-2">
            <button
                class="btn btn-sm btn-primary"
                @click="$emit('append-new-route', type)"
            >Добавить маршрут</button>
        </div>
    </div>
</template>
<script>
import draggable from 'vuedraggable';
import VueTimepicker from 'vue2-timepicker/src/vue-timepicker';
import OpenMapLinkBtns from "./OpenMapLinkBtns";
import CopyBtn from "./CopyBtn";

export default {
    components: {
        CopyBtn,
        OpenMapLinkBtns,
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
            },
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
                return person[`${this.type}_address`];
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
