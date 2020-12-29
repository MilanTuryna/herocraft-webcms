class PlayerAPI {
    /**
     * @param nickname
     */
    constructor(nickname) {
        this.apiURL = window.location.origin + "/statistiky/api/" + nickname;
        this.nickname = nickname;
    }

    /**
     * @param then
     * @param err
     * @param loaded
     */
    static getStatus(then, err, loaded) {
        axios
            .get(window.location.origin + "/statistiky/api/testtesttesttesttest")
            .then(then)
            .catch(err)
            .finally(loaded)
    }

    /**
     * @param then
     * @param err
     * @param loaded
     */
    callAPI(then, err, loaded) {
        axios
            .get(this.apiURL)
            .then(then)
            .catch(err)
            .finally(loaded);
    }
}
