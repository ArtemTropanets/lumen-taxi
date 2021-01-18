<template>
    <div class="modal fade" id="createEditPersonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cотрудник</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <FormControlInput
                        v-model="person.name"
                        label="Имя"
                        id="createPersonModalName"
                        placeholder="Пупкин"
                    />

                    <FormControlInput
                        v-model="person.phone"
                        label="Телефон"
                        id="createPersonModalPhone"
                    />

                    <FormControlInput
                        v-model="person.default_evening_address"
                        label="Стандартный адрес вечер"
                        id="createPersonModalEveningAddress"
                    />

                    <FormControlInput
                        v-model="person.default_morning_address"
                        label="Стандарнтый адрес утро"
                        id="createPersonModalMorningAddress"
                    />

                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-between">
                        <div>

                        </div>

                        <div>
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >Закрыть</button>

                            <button
                                type="button"
                                class="btn btn-primary"
                                @click="saveHandler"
                            >Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import FormControlInput from "../components/FormControlInput";

import PersonService from "../services/PersonService";

export default {
    name: "CreateEditPersonModal",


    components: {
        FormControlInput
    },


    data() {
        return {
            modal: null,
            person: {
                name: '',
                phone: '',
                default_morning_address: '',
                default_evening_address: '',
            },
            modalType: null, // 'create', 'edit'
        }
    },


    methods: {
        showModal(modalType) {
            this.modalType = modalType;
            if (this.modalType === 'create') {
                this.resetCreatePersonObj();
                this.modal.show();
            } else if (this.modalType === 'edit') {

            }
        },

        resetCreatePersonObj() {
            this.person = {
                name: '',
                phone: '',
                default_morning_address: '',
                default_evening_address: '',
            };
        },

        saveHandler() {
            if (this.modalType === 'create') {
                this.createPerson();
            } else if (this.modalType === 'edit') {

            }
        },

        createPerson() {
            if (!this.person.name.trim()) {
                alert('Человек без имени');
                return;
            }

            this.$eventBus.$emit('show-loader');

            PersonService.createPerson(this.person)
                .then(response => {
                    if (response.data.status !== 'success') {
                        throw new Error('Unexpected response');
                    }

                    this.modal.hide();
                })
                .catch(error => {
                    console.dir(error);
                    alert('Error');
                })
                .finally(() => {
                    this.$eventBus.$emit('show-loader', false);
                    this.$eventBus.$emit('load-persons');
                });
        },
    },


    created() {
        this.$eventBus.$on('show-create-edit-person-modal', this.showModal);
    },

    mounted() {
        this.modal = new bootstrap.Modal(document.getElementById('createEditPersonModal'));
    }
}
</script>
