/**
 * @author Milan Turyna
 * @see https://github.com/MilanTuryna
 */
class ElementsEffect {
    constructor(elements, callback = function (element) {}) {
        this.elementsArr = [...elements];
        this.callback = callback;
    }

    getElementsArr() {
        return this.elementsArr;
    }
}

class CounterEffect extends ElementsEffect {
    start() {
        this.elementsArr.forEach((element) => {
            let counter = element.dataset["counterEffect-startCounter"] || 0;
            let counterGoal = element.dataset["counterEffect-startCounter"] || 100;
            let counterChar = element.dataset["counterEffect-counterChar"] || "%";
            let intervalTimeout = element.dataset["hundredPercents-timeout"] || 50;

            let interval = setInterval(() => {
                if(counter === counterGoal) {
                    clearInterval(interval);
                    this.callback(element);
                    return;
                }
                counter++;
                element.innerHTML = counter + counterChar;
            }, intervalTimeout);
        });
    }
}
class TypeWriterEffect extends ElementsEffect {
    start() {
        let self = this;
        this.elementsArr.forEach(function (element) {
            let splitText = element.innerText.split('');
            let intervalTimeout = element.dataset["typeWriter-timeout"] || 50;
            let counter = element.dataset['typeWriter-counter'] || 1;
            let interval;

            element.innerHTML = splitText[0];

            interval = setInterval(() => {
                if(counter === splitText.length) {
                    clearInterval(interval);
                    self.callback(element);
                    return;
                }

                element.innerHTML += splitText[counter];
                counter++;
            }, intervalTimeout);
        });
    }
}