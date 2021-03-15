<template>
    <div class="person-table-container">
        <table class="person-table container">
            <thead>
                <tr>
                    <th class="text-center">Сотрудник</th>
                    <th class="col-4 text-center"
                        style="z-index: 1;"
                    >Адрес вечер</th>
                    <th class="col-4 text-center"
                        style="z-index: 1;"
                    >Адрес утро</th>
                    <th class="col-1"></th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="person of persons"
                    :key="person.id"
                    class="person-table__row"
                    :class="{'address-saved-today': isAddressSavedToday(person.address_update_date)}"
                >
                    <td>{{ person.name }}</td>
                    <td>
                        <div class="input-group">
                            <AddressListInputGroupBtn
                                :isHoveringObj="isHoveringObj.evening_home"
                                :person="person"
                                btnType="home"
                                dayTime="evening"
                                @click="setHomeAddress(person, 'evening')"
                            />
                            <input
                                v-model="person.evening_address"
                                type="text"
                                class="form-control"
                            >
                            <AddressListInputGroupBtn
                                :isHoveringObj="isHoveringObj.evening_delete"
                                :person="person"
                                btnType="delete"
                                @click="deleteAddress(person, 'evening')"
                            />
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <AddressListInputGroupBtn
                                :isHoveringObj="isHoveringObj.morning_home"
                                :person="person"
                                btnType="home"
                                dayTime="morning"
                                @click="setHomeAddress(person, 'morning')"
                            />
                            <input
                                v-model="person.morning_address"
                                type="text"
                                class="form-control"
                            >
                            <AddressListInputGroupBtn
                                :isHoveringObj="isHoveringObj.morning_delete"
                                :person="person"
                                btnType="delete"
                                @click="deleteAddress(person, 'morning')"
                            />
                        </div>
                    </td>
                    <td>
                        <button
                            class="btn btn-success"
                            @click="saveTodayAddresses(person, $event)"
                        >Сохранить</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import PersonService from "../services/PersonService";
import AddressListInputGroupBtn from "../components/AddressListInputGroupBtn";

import {toLocalIsoString} from "../services/helpers";

export default {
    components: {AddressListInputGroupBtn},


    props: {
        personsProp: {
            type: Array,
            required: true,
        },
    },


    data() {
        return {
            persons: this.personsProp,
            todayIsoDate: null,
            isHoveringObj: {
                'evening_delete': {},
                'evening_home': {},
                'morning_delete': {},
                'morning_home': {},
            },
        }
    },


    methods: {
        updatePersonsTable() {
            this.updateTodayIsoDate();

            this.$eventBus.$emit('show-loader');
            PersonService.getActive()
                .then(response => {
                    this.persons = response.data.persons;
                })
                .catch(error => {
                    console.dir(error);
                    alert('Error');
                })
                .finally(() => this.$eventBus.$emit('show-loader', false));
        },

        isAddressSavedToday(addressUpdateDate) {
            return addressUpdateDate === this.todayIsoDate;
        },

        updateTodayIsoDate() {
            this.todayIsoDate = toLocalIsoString(new Date());
        },

        setHomeAddress(person, type) {
            person[`${type}_address`] = person[`default_${type}_address`];
        },

        deleteAddress(person, type) {
            person[`${type}_address`] = '';
        },

        saveTodayAddresses(person, event) {
            const btn = event.currentTarget;
            setTimeout(() => {
                btn.blur();
            }, 100);

            this.$eventBus.$emit('show-loader');
            PersonService.saveTodayAddresses({
                person_id: person.id,
                morning_address: person.morning_address,
                evening_address: person.evening_address,
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
                    this.updatePersonsTable();
                });
        },
    },


    created() {
        this.updateTodayIsoDate();
    },
}
</script>

<style lang="scss" scoped>
table {
    border-collapse: separate; /* Don't collapse */
    border-spacing: 0;
}

.person-table-container {
    height: calc(100vh - 60px);
    overflow: auto;
}

.person-table {
    width: 100%;
    position: relative;

    th, td {
        padding: 10px;
    }

    th {
        position: sticky;
        top: 0;
        background: white;
        border-bottom: 1px solid lightgrey;
    }

    &__row {
        transition: all .1s ease;

        &.address-saved-today {
            background-color: #bef9be;

            &:hover {
                background-color: lightgreen;
            }

            &:focus-within {
                background-color: lightgreen;
            }
        }

        &:not(:last-child) td {
            border-bottom: 1px solid lightgrey;
        }

        &:hover {
            background-color: #def0ff;
        }

        &:focus-within {
            background-color: #def0ff;
        }
    }
}
</style>
