/*function countdown(due){
    const now = new Date();

    const rest = due.getTime() - now.getTime();
    const sec = Math.floor(rest/1000)%60;
    const min = Math.floor(rest/1000/60)%60;
    const hours = Math.floor(rest/1000/60/60)%24;
    const days = Math.floor(rest/1000/60/60/24);
    const count = [days,hours,min,sec];
    return count;
}
const goal = new Date(2020,9,29);

function recalc(){
    const counter = countdown(goal);
   document.getElementById('day').textContent = counter[0];
   document.getElementById('hour').textContent = counter[1];
   document.getElementById('min').textContent = String(counter[2]).padStart(2, '0');
   document.getElementById('sec').textContent = String(counter[3]).padStart(2, '0');
    refresh();
}

function refresh() {
    setTimeout(recalc,1000);
}

recalc();*/
//登録フォーム入力チェック
function check(){
	var flag = 0;
	// 設定開始（必須にする項目を設定してください）
	if(document.form0.sirialno.value == ""){ // 「商品番号」の入力をチェック
		flag = 1;
	}
	else if(document.form0.goods.value == ""){ // 「商品名」の入力をチェック
		flag = 1;
	}
	else if(document.form0.number.value == ""){ // 「数量」の入力をチェック
		flag = 1;
	}
	else if(document.form0.price.value == ""){ // 「料金」の入力をチェック
		flag = 1;
	}
    else if(document.form0.date.value == ""){ // 「期限」の入力をチェック
		flag = 1;
	}
	// 設定終了
	if(flag){
		window.alert('項目未入力がありました'); // 入力漏れがあれば警告ダイアログを表示
		return false; // 送信を中止
	}
	else{
		return true; // 送信を実行
	}
}

function checkedit(){
	var flag = 0;
	// 設定開始（必須にする項目を設定してください）
	if(document.form2.edit.value == ""){ // 「編集したい商品番号」の入力をチェック
		flag = 1;
	}
	else if(document.form2.editnum.value == ""){ // 「編集後の数量」の入力をチェック
		flag = 1;
	}
	else if(document.form2.editprice.value == ""){ // 「編集後の料金」の入力をチェック
		flag = 1;
	}
	// 設定終了
	if(flag){
		window.alert('項目に未入力がありました'); // 入力漏れがあれば警告ダイアログを表示
		return false; // 送信を中止
	}
	else{
		return true; // 送信を実行
	}
}



