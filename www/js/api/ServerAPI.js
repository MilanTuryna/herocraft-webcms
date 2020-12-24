/**
 * Constant with functions working with API for server. (/statistiky/api - ServerAPI, /statistiky/api/<player> - PlayerAPI)
 * @type {{callAPI: ServerAPI.callAPI}}
 */
const ServerAPI = {
    /**
     * @param then
     * @param err
     * @param loaded
     */
    callAPI: function (then, err, loaded) {
        fetch(window.location.origin + "/statistiky/api").then(then).catch(err).finally(loaded);
    }
};