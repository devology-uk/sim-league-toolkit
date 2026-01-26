const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

const isProduction = process.env.NODE_ENV === 'production';
const devServerPort = 3000;

module.exports = {
    ...defaultConfig,
    entry: {
        'admin/index': path.resolve(__dirname, 'src/admin/index.tsx'),
        // Add block entries here as needed
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
};