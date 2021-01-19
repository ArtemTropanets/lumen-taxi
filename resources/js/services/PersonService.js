export default {
    getPersons: () => axios.get('/person/getAll'),

    saveTodayAddresses: (data) => axios.post('/person/saveTodayAddresses', data),

    createPerson: (data) => axios.post('/person/create', data),

    editPerson: (data) => axios.post('/person/edit', data),

    softDeletePerson: (id) => axios.delete(`/person/softDelete/${id}`),

    restorePerson: (data) => axios.post('/person/restore', data),
}
