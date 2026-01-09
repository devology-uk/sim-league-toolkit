const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require('path');

module.exports = {
  ...defaultConfig,
  entry: {
    ...defaultConfig.entry(),
    "admin/index": resolve(process.cwd(), "./src/admin", 'index.tsx'),
  }
}