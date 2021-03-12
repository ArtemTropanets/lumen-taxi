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
                        <td>
                            <button
                                v-if="person.phone"
                                class="btn btn-secondary btn-sm text-dark bg-light"
                                style="border-color: lightgrey"
                                :ref="`copyPhoneBtn_${person.id}`"
                                @click="copyToClipboard(person)"
                            >
                                <svg
                                    v-if="copiedPhone[person.id]"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-check2-circle" viewBox="0 0 16 16">
                                    <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                                    <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
                                </svg>
                                <svg
                                    v-else
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-phone" viewBox="0 0 16 16">
                                    <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
                                    <path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                </svg>
                            </button>
                        </td>
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
            },
            copiedPhone: {

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
                return person[`${this.type}_address`]
            });

            this.$eventBus.$emit('show-route-map-modal', addresses, this.type);
        },

        copyToClipboard(person) {
            if (!navigator.clipboard) {
                alert('Браузер не поддерживает');
                return;
            }

            navigator.clipboard.writeText(person.phone)
                .then(() => {
                    this.$set(this.copiedPhone, person.id, true);

                    this.$refs[`copyPhoneBtn_${person.id}`][0].blur();

                    setTimeout(() => {
                        this.copiedPhone[person.id] = false;
                    }, 1000);
                });
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
