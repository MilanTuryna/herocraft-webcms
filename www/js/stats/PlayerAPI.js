class PlayerAPI {
    constructor(nickname) {
        this.apiURL = 'https://test.turyna.eu/statistiky/api/' + nickname;
        this.nickname = nickname;
    }

    static getStatus(then, err, loaded) {
        axios
            .get('https://test.turyna.eu/statistiky/api/testtesttesttesttest')
            .then(then)
            .catch(err)
            .finally(loaded)
    }

    callAPI(then, err, loaded) {
        axios
            .get(this.apiURL)
            .then(then)
            .catch(err)
            .finally(loaded);
    }
}
