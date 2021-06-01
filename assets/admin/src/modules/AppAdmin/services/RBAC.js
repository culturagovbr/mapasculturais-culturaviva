
/**
 * Lista das funções e níveis de acesso existente na aplicação
 *
 *
 * Inspirado em: https://raw.githubusercontent.com/fnakstad/angular-client-side-auth/master/client/js/routingConfig.js
 *
 * @param {type} exports
 * @returns {undefined}
 */
window['RBAC'] = (function () {
    var exports = {};
    var config = {
        /**
         * Lista das funções existentes dentro do app
         */
        roles: [
            'GUEST', // Usuário não logado ou role não cadastrada
            'rcv_agente_area',
            'rcv_certificador_civil',
            'rcv_certificador_publico',
            'rcv_certificador_minerva'
        ],
        /**
         * Criação dos níveis de acesso, referenciando as funções acima.
         *
         * Pode-se usar o asteristico "*" para representar o acesso de todas as funções.
         */
        accessLevels: {
            PUBLIC: "*",
            AGENTES: [
                'rcv_agente_area',
                'rcv_certificador_civil',
                'rcv_certificador_publico',
                'rcv_certificador_minerva'
            ],
            CERTIFICADORES: [
                'rcv_certificador_civil',
                'rcv_certificador_publico',
                'rcv_certificador_minerva'
            ],
            AGENTE_AREA: ['rcv_agente_area'],
            CERTIFICADOR_CIVIL: ['rcv_certificador_civil'],
            CERTIFICADOR_PUBLICO: ['rcv_certificador_publico'],
            CERTIFICADOR_MINERVA: ['rcv_certificador_minerva']
        }
    };

    exports.ROLE = buildRoles(config.roles);
    exports.ACCESS_LEVEL = buildAccessLevels(config.accessLevels, exports.ROLE);

    /**
     * Verifica se a função informada tem acesso a esse resource
     *
     * @param {AccessLevel} accessLevel
     * @param {Role} role
     * @returns {unresolved}
     */
    exports.authorize = function (accessLevel, role) {
        if (Array.isArray(role)) {
            for (var a = 0, l = role.length; a < l; a++) {
                if (exports.authorize(accessLevel, role[a])) {
                    return true;
                }
            }
            return false;
        }

        if (typeof role === 'string') {
            // Busca a referencia correta para a role
            if (exports.ROLE[role]) {
                role = exports.ROLE[role];
            } else {
                // Role não cadastrada
                role = exports.ROLE.GUEST;
            }
        }

        return role && !!(accessLevel.bitMask & role.bitMask);
    };

    return exports;

    function Role(bitMask, title) {
        this.bitMask = bitMask;
        this.title = title;
    }

    function AccessLevel(bitMask) {
        this.bitMask = bitMask;
    }

    /**
     * Method to build a distinct bit mask for each role
     * It starts off with "1" and shifts the bit to the left for each element in the
     * roles array parameter
     *
     * @param {type} roles
     * @returns {unresolved}
     */
    function buildRoles(roles) {

        var bitMask = "01";
        var userRoles = {};

        for (var role in roles) {
            var intCode = parseInt(bitMask, 2);
            userRoles[roles[role]] = new Role(intCode, roles[role]);
            bitMask = (intCode << 1).toString(2);
        }

        return userRoles;
    }

    /**
     * This method builds access level bit masks based on the accessLevelDeclaration parameter which must
     * contain an array for each access level containing the allowed user roles.
     *
     * @param {type} accessLevelDeclarations
     * @param {type} userRoles
     * @returns {unresolved}
     */
    function buildAccessLevels(accessLevelDeclarations, userRoles) {

        var accessLevels = {};
        for (var level in accessLevelDeclarations) {

            if (typeof accessLevelDeclarations[level] === 'string') {
                if (accessLevelDeclarations[level] === '*') {

                    var resultBitMask = '';

                    for (var role in userRoles) {
                        resultBitMask += "1";
                    }
                    //accessLevels[level] = parseInt(resultBitMask, 2);
                    accessLevels[level] = {
                        bitMask: parseInt(resultBitMask, 2)
                    };
                } else {
                    console.log("Access Control Error: Could not parse '" + accessLevelDeclarations[level] + "' as access definition for level '" + level + "'");
                }
            } else {
                var resultBitMask = 0;
                for (var role in accessLevelDeclarations[level]) {
                    if (userRoles.hasOwnProperty(accessLevelDeclarations[level][role])) {
                        resultBitMask = resultBitMask | userRoles[accessLevelDeclarations[level][role]].bitMask;
                    } else {
                        console.log("Access Control Error: Could not find role '" + accessLevelDeclarations[level][role] + "' in registered roles while building access for '" + level + "'");
                    }
                }

                accessLevels[level] = {
                    bitMask: resultBitMask
                };
            }
        }

        return accessLevels;
    }
})();