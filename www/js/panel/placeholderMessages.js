/**
 * TODO: use this script in panel templates
 * Changing placeholder by selected subject in elements with class 'js-subject-placeholder'
 * @type {{init: subjectsPlaceholderMessages.init, defaultPlaceholder: string}}
 */
const subjectsPlaceholderMessages = {
    defaultPlaceholder: "Např: Ahoj, žádám o ...",
    init: function (json, event) {
        let placeholderMessages = JSON.parse(json);
        let SPM = subjectsPlaceholderMessages;
        let value = event.value;
        let optionElement = document.querySelector('option[value="' + value + '"]');
        let label = optionElement.parentElement.getAttribute("label");
        let arrayGroup = placeholderMessages[label];
        [...document.getElementsByClassName("js--subject-placeholder")].forEach(el => arrayGroup
            ? el.placeholder = arrayGroup[value] || SPM.defaultPlaceholder
            : el.placeholder = placeholderMessages[value] || SPM.defaultPlaceholder);
    }
};