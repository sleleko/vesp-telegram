export default ({app}, inject) => {
    inject('hasScope', (scope) => {
        return app.$auth.loggedIn && (app.$auth.hasScope(scope) || app.$auth.hasScope(`${scope}/get`))
    });
}