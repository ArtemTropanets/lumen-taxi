<template>
    <div class="modal fade" id="createEditPersonModal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
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
                        placeholder="Имя Фамилия"
                    />

                    <FormControlInput
                        v-model="person.phone"
                        label="Телефон"
                        id="createPersonModalPhone"
                        placeholder="098-765-43-21"
                        @input="formatPhoneNumber"
                    />

                    <FormControlInput
                        v-model="person.default_evening_address"
                        label="Домашний адрес вечер"
                        id="createPersonModalEveningAddress"
                    />

                    <FormControlInput
                        v-model="person.default_morning_address"
                        label="Домашний адрес утро"
                        id="createPersonModalMorningAddress"
                    />

                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button
                                v-show="modalType === 'edit'"
                                class="btn btn-danger"
                                @click="deletePerson"
                            >Удалить сотрудника</button>
                        </div>

                        <div>
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >Закрыть</button>

                            <button
                                type="button"
                                class="btn btn-success"
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
        showModal(modalType, person = null) {
            this.modalType = modalType;
            if (this.modalType === 'create') {
                this.resetCreatePersonObj();
                this.modal.show();
            } else if (this.modalType === 'edit') {
                this.person = _.clone(person);
                this.modal.show();
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
            if (!this.person.name.trim()) {
                alert('Введи имя');
                return;
            }

            this.$eventBus.$emit('show-loader');

            PersonService[`${this.modalType}Person`](this.person)
                .then(response => {
                    if (response.data.status !== 'ok') {
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
                    this.$eventBus.$emit('show-success-toast');
                    this.$emit('load-persons');
                });
        },

        deletePerson() {
            const password = window.prompt(
                `Ты действительно хочешь удалить ${this.person.name}? Это действие нельзя отменить. Введи пароль`
            );

            if (password === null) return;
            if (password !== 'ololo') {
                alert('Неверный пароль');
                return;
            }

            PersonService.delete(this.person.id)
                .then(response => {
                    if (response.data.status !== 'ok') {
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
                    this.$eventBus.$emit('show-success-toast');
                    this.$emit('load-persons');
                });
        },

        formatPhoneNumber() {
            if (!this.person.phone) {
                this.person.phone = '';
            }

            setTimeout(() => {
                if (this.person.phone.length > 13) {
                    this.person.phone = this.person.phone.slice(0, 13);
                    return;
                }
                this.person.phone = this.person.phone.replace(/\D/g, '');

                if (this.person.phone.slice(0, 2) === '38') {
                    this.person.phone = this.person.phone.slice(2);
                }

                if (['3','8'].includes(this.person.phone[0])) {
                    this.person.phone = this.person.phone.slice(1);
                }

                if (this.person.phone.length < 7) {
                    this.person.phone = this.person.phone.replace(/(\d{3})(\d{1,3})/,
                        '$1-$2');
                    return;
                } else if (this.person.phone.length < 9) {
                    this.person.phone = this.person.phone.replace(/(\d{3})(\d{3})(\d{1,2})/,
                        '$1-$2-$3');
                    return;
                }

                this.person.phone = this.person.phone.replace(/(\d{3})(\d{3})(\d{2})(\d{1,2})/,
                    '$1-$2-$3-$4');
            });

        },
    },

    created() {
        this.$eventBus.$on('show-create-edit-person-modal', this.showModal);
    },

    mounted() {
        this.modal = new bootstrap.Modal(document.getElementById('createEditPersonModal'), {
            backdrop: 'static',
        });
    },

    destroyed() {
        this.modal = null;
        this.$eventBus.$off('show-create-edit-person-modal', this.showModal);
    },
}
</script>
