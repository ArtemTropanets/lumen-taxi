export const capitalizeFirstLetter = (string) => string[0].toUpperCase() + string.slice(1);

export const toLocalIsoString = (date) => {
    return date.getFullYear() + '-'
        + String(date.getMonth() + 1).padStart(2, '0') + '-'
        + String(date.getDate()).padStart(2, '0');
}
