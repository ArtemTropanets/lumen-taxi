export default {
    getPersons: () => axios.get('/person/getAll'),

    saveTodayAddresses: (data) => axios.post('/person/saveTodayAddresses', data),

    createPerson: (data) => axios.post('/person/create', data),
}
