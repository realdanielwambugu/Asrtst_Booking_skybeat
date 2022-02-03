module.exports = {
   plugins: [
       require("postcss-uncss")({
           html: [
             './index.html',
             './templates/customer/Auth/*.html'
           ]
       }),
       require('cssnano')({
           preset: 'default',
       }),
   ]
}
