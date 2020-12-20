<template>
    <div>
        <div class="person-table-container">
            <table class="person-table">
                <thead>
                <tr>
                    <th class="text-center">Сотрудник</th>
                    <th class="col-4 text-center">Адрес вечер</th>
                    <th class="col-4 text-center">Адрес утро</th>
                    <th class="col-1"></th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="person in persons"
                    :key="person.id"
                    class="person-table__row"
                    :class="{'address-saved-today': isAddressSavedToday(person.address_update_date)}"
                >
                    <td class="text-center">{{ person.name }}</td>
                    <td>
                        <input
                            v-model="person.evening_address"
                            type="search"
                            class="form-control"
                        >
                    </td>
                    <td>
                        <input
                            v-model="person.morning_address"
                            type="search"
                            class="form-control"
                        >
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
    </div>
</template>

<script>
import PersonService from "../services/PersonService";
export default {
    name: "DailyAddressList",

    data() {
        return {
            persons: null,
            todayIsoDate: null,
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
            this.todayIsoDate = new Date().toISOString().slice(0, 10);
        },

        saveTodayAddresses(person) {
            this.$eventBus.$emit('show-loader');
            PersonService.saveTodayAddresses({
                person_id: person.id,
                morning_address: person.morning_address,
                evening_address: person.evening_address,
            })
                .then(response => {
                    if (response.data.status !== 'success') {
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
        this.updatePersonsTable();
    }
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
