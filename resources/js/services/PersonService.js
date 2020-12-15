export default {
    getPersons() {
        return axios.get('/person/getAll');
    }
}
