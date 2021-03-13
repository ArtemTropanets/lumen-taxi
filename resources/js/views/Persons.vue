<template>
    <div class="person-table-container">
        <CreateEditPersonModal
            @load-persons="loadPersons"
        />

        <table class="person-table">
            <thead>
            <tr>
                <th class="text-center">Сотрудник</th>
                <th>Телефон</th>
                <th class="text-center">Домашний адрес вечер</th>
                <th class="text-center">Домашний адрес утро</th>
                <th colspan="2" style="text-align: right;">
                    <button
                        class="btn btn-primary btn-sm"
                        @click="$eventBus.$emit('show-create-edit-person-modal', 'create')"
                    >Добавить сотрудника</button>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="person of persons"
                :key="person.id"
                class="person-table__row"
                :class="{'inactive-person': person.deleted_at}"
            >
                <td>{{ person.name }}</td>
                <td>{{ person.phone }}</td>
                <td>{{ person.default_evening_address }}</td>
                <td>{{ person.default_morning_address }}</td>
                <td>
                    <button
                        class="btn btn-secondary btn-sm text-dark bg-light"
                        style="border-color: lightgrey"
                        @click="$eventBus.$emit('show-create-edit-person-modal', 'edit', person)"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                        </svg>
                    </button>
                </td>
                <td>
                    <button
                        v-if="person.deleted_at"
                        class="btn btn-success btn-sm"
                        style="width: 120px"
                        @click="activatePerson(person.id)"
                    >Активировать</button>
                    <button
                        v-else
                        class="btn btn-danger btn-sm"
                        style="width: 120px"
                        @click="deactivatePerson(person.id)"
                    >Деактивировать</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>


<script>
import CreateEditPersonModal from "../modals/CreateEditPersonModal";
import PersonService from "../services/PersonService";

export default {
    components: {
        CreateEditPersonModal,
    },


    props: {
        personsProp: {
            type: Array,
            required: true,
        },
    },


    data() {
        return {
            persons: this.personsProp,
        };
    },


    methods: {
        loadPersons() {
            this.$eventBus.$emit('show-loader');
            PersonService.getAll()
                .then(response => {
                    this.persons = response.data.persons;
                })
                .catch(error => {
                    console.dir(error);
                    alert('Error');
                })
                .finally(() => this.$eventBus.$emit('show-loader', false));
        },

        activatePerson(personId) {
            this.$eventBus.$emit('show-loader');
            PersonService.activate({
                person_id: personId,
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
                    this.loadPersons();
                });
        },

        deactivatePerson(personId) {
            this.$eventBus.$emit('show-loader');
            PersonService.deactivate(personId)
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
                    this.loadPersons();
                });
        },
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

        &.inactive-person {
            background-color: #ffcdcd;

            &:hover {
                background-color: #ffb1b1;
            }

            &:focus-within {
                background-color: #ffb1b1;
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
