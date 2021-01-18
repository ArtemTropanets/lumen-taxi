<template>
    <div class="mb-3 row align-items-center">
        <label :for="id" class="col-sm-2 col-form-label cursor-pointer">{{ label }}</label>
        <div class="col-sm-10">
            <input
                :value="value"
                type="text"
                class="form-control"
                :id="id"
                :placeholder="placeholder"
                @input="$emit('input', $event.target.value)"
            >
        </div>
    </div>
</template>
<script>
export default {
    name: 'FormControlInput',


    props: {
        value: String,
        label: String,
        id: String,
        placeholder: String,
        validate: Boolean,
    },
}
</script>
