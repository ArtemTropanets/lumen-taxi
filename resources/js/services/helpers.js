export const capitalizeFirstLetter = (string) => {
    return string[0].toUpperCase() + string.slice(1);
}

export const toLocalIsoString = (date) => {
    return date.getFullYear() + '-'
        + String(date.getMonth() + 1).padStart(2, '0') + '-'
        + String(date.getDate()).padStart(2, '0');
}

export const copyToClipboard = (text) => {
        if (!navigator.clipboard) {
            return fallbackCopyTextToClipboard(text);
        }

        return navigator.clipboard.writeText(text)
            .catch((error) => {
                console.dir(error);
                alert('Не удалось скопировать');
            });
}

const fallbackCopyTextToClipboard = (text) => {
    return new Promise((resolve, reject) => {
        const textArea = document.createElement("textarea");
        textArea.value = text;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (!successful) {
                throw new Error('Couldn\'t copy');
            }
            resolve();
        } catch (error) {
            console.dir(error);
            alert('Не удалось скопировать');
            reject();
        } finally {
            textArea.remove();
        }
    });
}
