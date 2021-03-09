export default {
    getRoutesForEdit: () => axios.get('/route/getRoutesForEdit'),

    saveRoutes: (data) => axios.post('/route/saveRoutes', data),
}
