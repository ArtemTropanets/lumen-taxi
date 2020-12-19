export default {
    getPersons: () => axios.get('/person/getAll'),

    saveTodayAddresses: (data) => axios.post('/person/saveTodayAddresses', data),
}
