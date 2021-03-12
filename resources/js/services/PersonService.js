export default {
    getActive: () => axios.get('/person/getActive'),

    getAll: () => axios.get('/person/getAll'),

    saveTodayAddresses: (data) => axios.post('/person/saveTodayAddresses', data),

    createPerson: (data) => axios.post('/person/create', data),

    editPerson: (data) => axios.post('/person/edit', data),

    deactivate: (id) => axios.delete(`/person/deactivate/${id}`),

    activate: (data) => axios.post('/person/activate', data),

    delete: (id) => axios.delete(`/person/delete/${id}`),
}
