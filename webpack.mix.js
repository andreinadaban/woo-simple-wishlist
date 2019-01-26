let mix = require('laravel-mix');
let imagemin = require('imagemin');
let imageminSvgo = require('imagemin-svgo');

mix.js('public/assets/src/js/wcsw-public.js', 'public/assets/dist/js')
    .setPublicPath('./')
    .options({
        processCssUrls: false
    })
    .disableNotifications()
    .then(() => {
        imagemin(['public/assets/src/svg/*.svg'], 'public/assets/dist/svg', {
            use: [
                imageminSvgo({
                    plugins: [
                        {
                            removeViewBox: false,
                            cleanupAttrs: true,
                            inlineStyles: true,
                            removeComments: true,
                            removeMetadata: true,
                            removeTitle: true,
                            removeDesc: true,
                            removeUselessDefs: true,
                            removeEmptyAttrs: true,
                            removeHiddenElems: true,
                            removeEmptyText: true,
                            removeEmptyContainers: true,
                            convertStyleToAttrs: true,
                            removeUnknownsAndDefaults: true,
                            cleanupIDs: true,
                            removeRasterImages: true,
                            convertShapeToPath: true,
                            removeDimensions: true,
                            removeStyleElement: true,
                            removeScriptElement: true
                        }
                    ]
                })
            ]
        });
    });
