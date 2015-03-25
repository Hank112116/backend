var dest = './public/',
    dev = './front/';

module.exports = {
    dest: {
        js: dest + 'js/',
        react: dest + 'react/',
        images: dest + 'images/'
    },

    src: {
        js: dev + 'js/',
        js_vendor: dev + 'js/vendor/',
        react: dev + 'react/',
        images: dev + 'images/'
    }
};