<template>
    <div v-tooltip="(btnType === 'home') && !person[`default_${dayTime}_address`] ? 'Домашний адрес не сохранен. Его можно сохранить на странице Сотрудники' : ''">
        <button
            class="input-group-text"
            @mouseover="$set(isHoveringObj, person.id, true)"
            @mouseout="isHoveringObj[person.id] = false"
            :class="{
                'btn-secondary': (isHoveringObj[person.id] && (btnType === 'home')),
                'btn-danger': (isHoveringObj[person.id] && (btnType === 'delete'))
            }"
            :disabled="(btnType === 'home') && !person[`default_${dayTime}_address`]"
            style="height: 100%;"
            @click="$emit('click')"
        >
            <svg v-if="btnType === 'home'" v-once
                 xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill"
                 viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M8 3.293l6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                <path fill-rule="evenodd"
                      d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
            </svg>
            <svg v-else-if="btnType === 'delete'"
                 xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
            </svg>
        </button>
    </div>
</template>
<script>
export default {
    props: {
        isHoveringObj: Object,
        person: Object,
        btnType: String,
        dayTime: String,
    }
}
</script>

<style>
    .input-group-text[disabled] {
        opacity: 0.4;
    }
</style>
