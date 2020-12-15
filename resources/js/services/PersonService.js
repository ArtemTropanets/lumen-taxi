export default {
    getPersons() {
        return axios.post('/person/getAll');
    }
}
