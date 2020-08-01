
// Ajax 通信で用いる Axios ライブラリの設定を記述しています

import { getCookieValue } from './util'

window.axios = require('axios')

// Ajaxリクエストであることを示すヘッダーを付与する
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.axios.interceptors.request.use(config => {
    // クッキーからトークンを取り出してヘッダーに添付する
    config.headers['X-XSRF-TOKEN'] = getCookieValue('XSRF-TOKEN')
    
    return config
})

window.axios.interceptors.response.use(
    response => response, //第一引数。成功時の処理"response"をそのまま返している。
    error => error.response || error //第二引数。失敗時の処理。インターセプターにまとめた。
)