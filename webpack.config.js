var Encore = require('@symfony/webpack-encore');

Encore

        // the project directory where compiled assets will be stored
        .setOutputPath('public/build/')

        // the public path used by the web server to access the previous directory
        .setPublicPath('/build')

        // empty the outputPath dir before each build
        .cleanupOutputBeforeBuild()

        // show OS notifications when builds finish/fail
        .enableBuildNotifications()

        // allow legacy applications to use $/jQuery as a global variable
        // this allow to prevent require jquery in our scripts
        .autoProvidejQuery()

        // will output scripts
        .addEntry('scripts/admin-tag', './assets/scripts/admin-tag.js')
        .addEntry('scripts/admin-post', './assets/scripts/admin-post.js')

        // this creates a 'vendor.js' file with jquery and the bootstrap JS module
        // these modules will not be included in other scripts anymore
        .createSharedEntry('scripts/vendor', [
            './assets/scripts/vendor.js'
        ])

        // will output styles
        .addStyleEntry('styles/app', './assets/styles/app.scss')

        // source maps
        .enableSourceMaps(!Encore.isProduction())

        // uncomment if you use Sass/SCSS files
        .enableSassLoader()

        // uncomment to create hashed filenames (e.g. app.abc123.css)
        .enableVersioning(Encore.isProduction())

        ;

var config = Encore.getWebpackConfig();

if (typeof config.devServer !== "undefined") {
    
    config.devServer.open = true;
    config.devServer.proxy = {
      "/": "http://localhost:8000"
    };
    
}

module.exports = config;
