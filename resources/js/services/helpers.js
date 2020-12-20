export const capitalizeFirstLetter = (string) => string[0].toUpperCase() + string.slice(1);

export const toLocalIsoString = (date) => `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
