const defaultConfig = require('@wordpress/scripts/config/webpack.config.js');

module.exports = {
  ...defaultConfig,
  ...{
    entry: {
      ...defaultConfig.entry,
      "admin/raceNumbers/index": "./src/admin/raceNumbers/index.js",
      "admin/server/index": "./src/admin/server/index.js"
    }
  },
};