let Encore = require('@symfony/webpack-encore');
let read = require('fs-readdir-recursive');

// Javascript
let entryPointPathJs = './assets/js';
read(entryPointPathJs).forEach(file => {
    let name = file.substr(0, file.length - 3);
    name = name.replace(/\\/g, '/');
    Encore.addEntry('js/' + name, entryPointPathJs + '/' + file)
});

// Stylesheet
let entryPointPathCss = './assets/css';
read(entryPointPathCss).forEach(file => {
    let name = file.substr(0, file.length - 4);
    name = name.replace(/\\/g, '/');
    Encore.addEntry('css/' + name, entryPointPathCss + '/' + file)
});

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/js/app.js')
    .addStyleEntry('main', './assets/css/main.css')
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning()
    .autoProvidejQuery()
    .addLoader({
        test: /\.json$/i,
        include: [require('path').resolve(__dirname, 'node_modules/ckeditor')],
        loader: 'raw-loader',
        type: 'javascript/auto'
    })
;

module.exports = Encore.getWebpackConfig();
