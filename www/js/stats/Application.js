const Utils = {
    date: {
        addZeroBefore: function (n) {
            return (n < 10 ? '0' : '') + n;
        }
    }, url: {
        findParam(parameterName) {
            let result;
            let tmp = [];
            location.search
                .substr(1)
                .split("&")
                .forEach(function (item) {
                    tmp = item.split("=");
                    if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
                });
            return result;
        }
    }, string: {
        escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }, sklonovani(pocet, slova) {
            pocet = Math.abs(pocet);
            if (pocet === 1) return slova[0];
            if (pocet < 5 && pocet > 0) return slova[1];
            return slova[2];
        }
    }
};



let app = new Vue({
    data() {
        return {
            button: {
                player: null,
                input: document.getElementById('#playerInput')
            },
            messages: {
                api_online: 'Databáze statistik byla úspěšně načtena, aplikace by měla správně fungovat.',
                api_offline: 'Načtení databáze statistik byla neúspěšná, aplikace nebude fungovat správně',
            },
            api: {
                time_execute: Utils.date.addZeroBefore(new Date().getHours()) + ':' + Utils.date.addZeroBefore(new Date().getMinutes()),
                info: null,
                loading: true,
                errored: false
            },
            player: {
                active: false,
                loading: true,
            }
        }
    },
    methods: {
        setPlayer(data) {
            let date = new Date();
            let dateFormat = Utils.date.addZeroBefore(new Date().getHours()) + ':' + Utils.date.addZeroBefore(new Date().getMinutes());
            if (data.player.exists) {
                let auth = {
                    lastlogin: new Date(data.player.auth.lastlogin),
                    regtime: new Date(data.player.auth.regtime),
                };
                let friends = {
                    list: [],
                };
                data.player.friends.list.forEach(e => {
                    friends.list.push(`<a href="?player=${e.player_name}">${e.player_name}</a>`);
                });
                history.pushState(null, `Statistiky - ${data.player.nickname}`, '?player=' + data.player.nickname);
                bootbox.alert({
                    title: data.player.nickname + `<img src="${data.player.headImageURL}" style="width:20px; margin-left:5px;"/>`,
                    callback: () => history.pushState(null, 'Statistiky', window.location.href.split('?')[0]),
                    onEscape: false,
                    message:
                        `<p class="text-muted small">Požadavek zpracován v ${dateFormat}</p>` +
                        '<p>' +
                        `Tento hráč byl zaregistrován dne ${auth.regtime.getDate()}.${auth.regtime.getMonth()+1}.${auth.regtime.getFullYear()}` +
                        `, naposledy se přihlásil dne ${auth.lastlogin.getDate()}.${auth.lastlogin.getMonth()+1}.${auth.regtime.getFullYear()} v ` +
                        `${Utils.date.addZeroBefore(auth.lastlogin.getHours()) + ':' + Utils.date.addZeroBefore(auth.lastlogin.getMinutes())}` +
                        ` a je přibližně ${data.player.auth.userID}. zaregistrovaný hráč na našem networku.` +
                        '</p>'
                        +
                        `<div class="ticket bg-white" >
        <div class="ticket-head">
            <h5>
                <p>
                    <span>Přehled statistik</span>
                </p>
            </h5>
        </div>
        <hr style="margin:0">
        <div class="ticket-body bg-light border-left border-right border-bottom">
                    <div class="row" style="margin:0;">
                        <div class="col-sm-4 border-right padding-sm-bot-none" style="padding: 16px">
                            <b>Herní přezdívka</b>
                          
                        </div>
                        <div class="col-sm-8 padding-sm-top-none" style="padding: 16px">
                            ${data.player.nickname}
                             <img width="20" src="${data.player.headImageURL}">
                        </div>
                    </div>
                     <div class="row border-top" style="margin:0;">
                         <div class="col-sm-4 border-right padding-sm-bot-none" style="padding: 16px">
                            <b>Počet přátel</b>
                        </div>
                        <div class="col-sm-8 padding-sm-top-none" style="padding: 16px">
                            ${data.player.friends['count']} ${Utils.string.sklonovani(data.player.friends['count'], ['přítel', 'přátelé', 'přátel'])}
                            (${friends.list})
                        </div>
                    </div>
                    <div class="row border-top" style="margin:0;">
                                            <div class="col-sm-4 border-right padding-sm-bot-none" style="padding: 16px">
                            <b>PVPCoiny</b>
                        </div>
                        <div class="col-sm-8 padding-sm-top-none" style="padding: 16px">
                            ${data.player.tokens} ${Utils.string.sklonovani(data.player.tokens, ['coin', 'coiny', 'coinů'])}
                        </div>
                    </div>
                     <div class="row border-top" style="margin:0;">
                       <div class="col-sm-4 border-right padding-sm-bot-none" style="padding: 16px">
                            <b>Odehraný čas</b>
                        </div>
                        <div class="col-sm-8 padding-sm-top-none" style="padding: 16px">
                            ${data.player.playedTime || data.player.playedTime === 0 ? 'Máš odehráno ' + Math.round(data.player.playedTime/60/60) + ' ' + Utils.string.sklonovani(Math.round(data.player.playedTime/60/60), ['hodina', 'hodiny', 'hodin'])
                            : 'Na serveru proběhla změna, je potřeba se znovu připojit.'}
                        </div>
                    </div>
        </div>
    </div>`,
                    size: 'large',
                });
            } else {
                history.pushState(null, `Statistiky - ${this.button.player}`, '?player=' + this.button.player);
                bootbox.alert({
                    size: "large",
                    title: "Ouh, nastala chyba...",
                    message: "Vypadá to, že hráč " + Utils.string.escapeHtml(this.button.player) + " nebyl nalezen.",
                    callback: function () {
                        history.pushState(null, 'Statistiky', window.location.href.split('?')[0]);
                    }
                });
            }
        }, error(exception) {
            console.error(exception);
        }, submitPlayer() {
            let player = this.button.player;
            let playerAPI = new PlayerAPI(player);
            this.player.active = true;
            playerAPI.callAPI(
                data => this.setPlayer(data.data),
                exception => this.error(exception),
                () => this.player.loading = false);
        }
    }, watch: {
        'button.player': function () {
            if(this.button.player)
                if(!/^\w+$/i.test(this.button.player)) this.button.player = this.button.player.toString().substring(0, this.button.player.length - 1);
        }
    },
    mounted() {
        AOS.init();
        PlayerAPI.getStatus(response => {
            this.api.info = response.data;
        }, error => {
            this.api.error = error;
            console.log(error);
        }, () => this.api.loading = false);
        if (Utils.url.findParam('player')) {
            this.button.player = Utils.string.escapeHtml(Utils.url.findParam('player'));
            this.submitPlayer();
        }
    }
}).$mount('#app');