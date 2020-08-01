/**
 * クッキーの値を取得する
 *
 * @param{String} searchKey 検索するキー
 * @returns {String} キーに対応する値
 */

export function getCookieValue(searchKey) {
  // そもそもsearchKeyがundefinedだったら、空文字を返却
  if (typeof searchKey ===  'undefined') {
    return ''
  }
  
  let val = '';
  
  // ; でsplitする。さらに、=でsplitすることで、値だけを取り出せる。
  // この取り出した値と、関数の引数として渡ってきたsearchKeyが一致するものを探している。
  document.cookie.split(';').forEach(cookie => {
    const[key, value] = cookie.split('=');
    if (key === searchKey) {
      return val = value;
    }
  })
  
  //一致するものがなかったらそのまま返却する
  return val;
  
}

export const OK = 200
export const CREATED = 201
export const INTERNAL_SERVER_ERROR = 500
export const UNPROCESSABLE_ENTITY = 422