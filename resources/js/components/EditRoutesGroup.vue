<template>
    <div>
        <h4 class="text-center">{{ title }}</h4>

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


        <div
            v-for="(route, index) in routes"
            :key="route.id"
            class="mt-2 pb-1 rounded-2 overflow-hidden"
            style="border: 1px solid #bdbdbd"
        >
            <div class="position-relative pt-2 pb-2 bg-light"
                 style="height: 40px; border-bottom: 1px solid lightgrey;"
            >
                <div class="fw-bold text-center">Маршрут {{ index + 1 }}</div>
                <button
                    v-show="!route.persons.length"
                    class="position-absolute top-50 end-0 btn btn-sm btn-danger"
                    style="transform: translate(-5px, -50%)"
                    @click="$emit('delete-route', type, index)"
                >Удалить</button>
            </div>
            <draggable
                tag="table"
                v-model="route.persons"
                v-bind="dragOptions"
                :animation="200"
                ghost-class="moving-person-row"
                class="route-table"
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

export default {
    name: 'EditRoutesGroup',


    components: {
        draggable,
    },


    props: {
        title: String,
        type: String,
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
}
</script>

<style lang="scss" scoped>
.route-table {
    width: 100%;
    min-height: 30px;

    &__row {
        cursor: move;

        &:not(:last-child) td {
            border-bottom: 1px solid lightgrey;
        }
    }
}

.moving-person-row {
    background: #F7FAFC;
    outline: 1px solid #4299e1;
}
</style>
