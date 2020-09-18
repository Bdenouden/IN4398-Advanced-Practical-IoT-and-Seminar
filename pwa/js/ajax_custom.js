
function getAjaxUri(action, requestData ){
	//returns the desired get-url with your data
	var getUri = getInitUri();
	requestData = typeof requestData !== 'undefined' ? requestData : '';
	getUri += '&ACTION=' + action;
	for(key in requestData){
		getUri += '&'+ key + '=' + requestData[key];
	}
	console.log(getUri);
	return encodeURI(getUri);
}

function getInitUri(){
	return ('https:' == document.location.protocol ? 'https://' : 'http://') +window.location.hostname+window.location.pathname + '?AJAX=1';
}
