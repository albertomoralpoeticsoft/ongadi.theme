const path = require('path')

const MiniCssExtractPlugin = require('mini-css-extract-plugin')

const themename = 'astra-child-ongadi'
const destdir = path.join(__dirname, themename)
const themeplublic = '/wp-content/themes/' + themename

module.exports = env => {  
                                                    
  const input = Object.keys(env)[2] || ''
  const params = input.split('-')

  const section = params[0] || 'app' // app | block
  const target = params[1] || ''
  
  let paths

  switch(section) {

    case 'app':

      paths ={
        entryjs: './src/app/main.js',
        entryscss: './src/app/scss/main.scss',
        output: destdir  + '/js-css',
        public: themeplublic,
        cssfilename: 'main.css'
      }

      break;

    case 'block':

      paths ={
        entryjs: './src/block/' + target + '/main.js',
        entryscss: './src/block/' + target + '/main.scss',
        output: destdir  + '/block/' + target,
        public: themeplublic,
        cssfilename: 'main.css'
      }

      break;
  }

  return {
    context: __dirname,
    stats: 'minimal',
    watch: true,
    name: 'blank',
    entry: {
      main: paths.entryjs,
      maincss: paths.entryscss
    },
    output: {
      path: paths.output,
      publicPath: paths.public,
      filename: '[name].js'
    },
    mode: 'development',
    devtool: 'source-map',
    module: {
      rules: [
        {
          test: /\.jsx?$/,
          exclude: /node_modules/,
          use: [          
            { 
              loader: 'babel-loader',
              options: {
                presets: [
                  '@babel/preset-env',
                  '@babel/preset-react'
                ]
              }
            }
          ]
        },
        {
          test: /\.scss$/,
          exclude: /node_modules/,
          use: [
            { 
              loader: MiniCssExtractPlugin.loader
            },
            {
              loader: 'css-loader'
            },
            {
              loader: 'sass-loader'
            }
          ]
        },
        {
          test: /\.css$/,
          include: /node_modules/,
          use: [
            'style-loader',
            'css-loader'
          ]
        },
        // Assets
        {
          test: /\.(jpg|jpeg|png|gif|svg|woff|ttf|eot|mp3)$/,
          type: 'asset/resource',
          generator: {
            emit: false,
            filename: content => { 

              return content.filename.replace(themename, '')
            }
          }
        }
      ]
    },
    plugins: [
      new MiniCssExtractPlugin({
        filename: paths.cssfilename
      })
    ],
    resolve: {
      extensions: ['.js', '.jsx'],
      alias: {
        assets: path.join(destdir, 'assets')
      }
    }
  }
}