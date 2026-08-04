const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

const isProduction = process.env.NODE_ENV === 'production';
const devServerPort = 3000;

module.exports = {
    ...defaultConfig,
    entry: {
        'admin/index': path.resolve(__dirname, 'src/admin/index.tsx'),
        'blocks/championship-tile/index': path.resolve(__dirname, 'src/blocks/championship-tile/edit.tsx'),
        'blocks/championship-tile/view': path.resolve(__dirname, 'src/blocks/championship-tile/view.js'),
        'blocks/championship-list/index': path.resolve(__dirname, 'src/blocks/championship-list/edit.tsx'),
        'blocks/event-tile/index': path.resolve(__dirname, 'src/blocks/event-tile/edit.tsx'),
        'blocks/event-tile/view': path.resolve(__dirname, 'src/blocks/event-tile/view.js'),
        'blocks/event-list/index': path.resolve(__dirname, 'src/blocks/event-list/edit.tsx'),
        'blocks/visibility/index': path.resolve(__dirname, 'src/blocks/visibility/edit.tsx'),
        'blocks/my-events/index': path.resolve(__dirname, 'src/blocks/my-events/edit.tsx'),
        'blocks/my-results/index': path.resolve(__dirname, 'src/blocks/my-results/edit.tsx'),
        'blocks/latest-results/index': path.resolve(__dirname, 'src/blocks/latest-results/edit.tsx'),
        'blocks/my-trophies/index': path.resolve(__dirname, 'src/blocks/my-trophies/edit.tsx'),
        'blocks/joinable-items/index': path.resolve(__dirname, 'src/blocks/joinable-items/edit.tsx'),
        'blocks/tab/index': path.resolve(__dirname, 'src/blocks/tab/edit.tsx'),
        'blocks/tabs/index': path.resolve(__dirname, 'src/blocks/tabs/edit.tsx'),
        'blocks/tabs/view': path.resolve(__dirname, 'src/blocks/tabs/view.js'),
    },
    devServer: {
        port: devServerPort,
        hot: true,
        liveReload: true,
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
            'Access-Control-Allow-Headers': 'X-Requested-With, content-type, Authorization',
        },
        allowedHosts: 'all',
        devMiddleware: {
            writeToDisk: true,
        },
        client: {
            webSocketURL: {
                hostname: 'localhost',
                pathname: '/ws',
                port: devServerPort,
            },
        },
    },
    output: {
        ...defaultConfig.output,
        path: path.resolve(__dirname, 'build'),
        publicPath: isProduction ? 'auto' : `http://localhost:${devServerPort}/`,
    },
    optimization: {
        ...defaultConfig.optimization,
        // Module concatenation ("scope hoisting") crashes here with "Unexpected end of JSON input"
        // inside webpack's own ConcatenationScope.matchModuleReference. Disabling it avoids the
        // crash; only cost is slightly larger (unconcatenated) bundles.
        concatenateModules: false,
    },
};