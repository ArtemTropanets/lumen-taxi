<template>
    <div>
        <div v-for="person in persons"
             :key="person.id"
        >
            <span>{{ person.name }}</span>
        </div>
    </div>
</template>

<script>
import PersonService from "../services/PersonService";
export default {
    name: "DailyAddressList",

    data() {
        return {
            persons: null,
        }
    },

    created() {
        PersonService.getPersons()
            .then(response => {
                this.persons = response.data.persons;
            })
            .catch(error => {
                console.dir(error);
                alert('Error');
            });
    }
}
</script>
