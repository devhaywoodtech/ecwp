var path= require('path')
module.exports = {  
    mode: "development",
    //mode: "production",
    externals: {
        "react": "React",
        "react-dom": "ReactDOM"
    },
    entry: {
      admin : "./admin/settings.js",
      calendar : "./calendar.js",
    },
    output: {
      filename: '[name].js',
      path: path.resolve(__dirname, 'dist'),
      chunkFilename: "[name].chunk.js"
    },
    module: {
      rules: [
        {
          test: /\.(js|jsx)$/,
          exclude: /node_modules/,
          use: {
            loader: "babel-loader"
          }
        }
      ]
    },
    optimization: {
      chunkIds: "named",
      runtimeChunk: true,
    },
    performance: {
      hints: false,
      maxEntrypointSize: 512000,
      maxAssetSize: 512000
    }  
};
