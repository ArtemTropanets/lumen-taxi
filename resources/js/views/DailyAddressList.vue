<template>
    <div class="person-table-container">
        <table class="person-table">
            <thead>
                <tr>
<!--                        <th></th>-->
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
<!--                        <td>-->
<!--                            <button-->
<!--                                class="btn btn-secondary btn-sm text-dark bg-light"-->
<!--                                style="border-color: lightgrey"-->
<!--                                @click="$eventBus.$emit('show-create-edit-person-modal', 'edit', person)"-->
<!--                            >-->
<!--                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">-->
<!--                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>-->
<!--                                </svg>-->
<!--                            </button>-->
<!--                        </td>-->
                    <td class="text-center">{{ person.name }}</td>
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
                            @click="saveTodayAddresses(person)"
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
            PersonService.getPersons()
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

        saveTodayAddresses(person) {
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
        this.$eventBus.$off('load-persons');
        this.$eventBus.$on('load-persons', this.updatePersonsTable);
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
