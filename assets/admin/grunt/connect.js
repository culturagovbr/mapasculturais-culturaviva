/* global module */

var rewriteModule = require('http-rewrite-middleware');

var serveStatic = require('serve-static');

module.exports = {
    development: {
        proxies: [
            {
                context: '/',
                host: 'local.culturaviva.gov.br',
                port: 80
            }
        ],
        options: {
            port: '<%= connectPort %>',
            hostname: 'localhost',
            livereload: '<%= connectLivereload %>',
            open: true,
            base: [
                '<%= distDir %>', // build
                '<%= srcDir %>' // fonte
            ],

            middleware: function (connect, options, middlewares) {
                var middlewares = [];
                if (!Array.isArray(options.base)) {
                    options.base = [options.base];
                }

                // Redirect SASS -> CSS
                middlewares.push(rewriteModule.getMiddleware([
                    {
                        from: '^(.*).scss$',
                        to: '$1.css',
                        redirect: 'temporary' // 302 Redirect
                    }
                ]));

                options.base.forEach(function (base) {
                    // Serve static files.
                    middlewares.push(['/admin', serveStatic(base)]);
//                    var static = serveStatic(base);
//                    middlewares.push(function (req, res, next) {
//                        console.log(req.url);
//                        if (req.url.indexOf('/admin') === 0) {
//                            return static(req, res, function(){
//                                return next();
//                            });
//                        }else{
//                            return next();
//                        }
//                    });
                });


                // proxy
                middlewares.push(require('grunt-connect-proxy/lib/utils').proxyRequest);

                return middlewares;
            }
        }
    },
    dist: {// Para visualizar
        options: {
            port: '<%= connectPort %>',
            hostname: 'localhost',
            open: true,
            keepalive: true,
            base: ['<%= distDir %>'],
            middleware: function (connect, options, middlewares) {

                // proxy
                middlewares.unshift(require('grunt-connect-proxy/lib/utils').proxyRequest);
            }
        }
    }
};
