/**
 * @type {{init: buttonStylePreview.init}}
 */
const buttonStylePreview = {
    /**
     * @param editorInstance
     * @param previewElement
     * @param buttonListElement
     * @param flashMessageElement
     * @param classInput
     * @param nameInput
     */
    init: function (editorInstance, previewElement, buttonListElement, flashMessageElement, classInput, nameInput) {
        function addMultiListener(elementList, eventList, callbackFunction) {
            elementList.forEach(element => events.forEach(event => element.addEventListener(event, callbackFunction)));
        }

        function callback() {
            classInput.value = classInput.value.replace(/\./g,'').replace(/\s/g, '-');
            if(!buttonListElement.classList.contains(classInput.value)) {
                flashMessageElement.style.display = 'none';
                previewElement.innerHTML = '';
                previewElement.innerHTML = '<style>' + editor.getValue() + '</style>';
                previewElement.innerHTML += "<button class='btn btn-sm " + classinput.value + "'>" + nameinput.value +  "</button>";
            } else {
                previewElement.innerHTML = '';
                flashMessageElement.style.display = 'block';
                flashMessageElement.innerText = 'Tlacitko s touto tridou jiz existuje!';
            }
        }

        let events = ["change", "onkeyup", "onkeydown"];
        let elements = [classInput, nameInput];
        addMultiListener(elements, events, callback);
        editorInstance.on('change', callback);
    }
};