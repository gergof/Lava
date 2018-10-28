const CopyWebpackPlugin=require("copy-webpack-plugin");

module.exports={
    mode: process.env.NODE_ENV || "developement",
    entry: "src/script/main.js",
    output: {
        path: "./dist",
        filename: "bundle.js"
    },
    module: {
        rules: [{
            test: /\.scss$/,
            use: [
                "style-loader",
                "css-loader",
                "sass-loader"
            ]
        }]
    },
    plugins: [
        new CopyWebpackPlugin({
            from: "src",
            to: "dest"
        })
    ]
};