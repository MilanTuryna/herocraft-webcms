class PlayerAPI {
    constructor(nickname) {
        this.apiURL = window.location.origin + "/statistiky/api" + nickname;
        this.nickname = nickname;
    }

    static getStatus(then, err, loaded) {
        axios
            .get(`${APIPath}/statistiky/api/testtesttesttesttest`)
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
