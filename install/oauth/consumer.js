var consumer = {};

consumer.change = {
	consumerKey : "default",
	consumerSecret : "default",
	token : 'e3fb98bb27b29dbed2059542c75ac447',
	tokenSecret : 'b91b25cddd30d85ab68f587377f2b126',
	serviceProvider : {
		signatureMethod : "HMAC-SHA1",
		tokenURL : "http://test302.inthause.c4.rd.devlinux.france.rbs.fr/changescriptexec.php"
	}
};

consumer.sendCommand = function sendCommand(form) {
	var accessor = consumer.change;
	var message = {
		action : accessor.serviceProvider.tokenURL,
		method : "POST",
		parameters : []
	};

	for ( var e = 0; e < form.elements.length; ++e) {
		var input = form.elements[e];
		if (input.name != null && input.name != "" && input.value != null
				&& input.value != "") {
			message.parameters.push( [ input.name, input.value ]);
		}
	}
	var requestBody = OAuth.formEncode(message.parameters);
	OAuth.setParameter(message, "oauth_signature_method", accessor.serviceProvider.signatureMethod);
	OAuth.completeRequest(message, accessor);
	var authorizationHeader = OAuth.getAuthorizationHeader("", message.parameters);

	var requestToken = new XMLHttpRequest();
	requestToken.open(message.method, message.action, true);
	
	requestToken.onreadystatechange = function (aEvt) {
		if (requestToken.readyState == 4) {
			document.getElementById('result').value = requestToken.status + " " + requestToken.statusText + "\n"
					+ requestToken.getAllResponseHeaders() + "\n"
					+ requestToken.responseText;
		}
	};
	requestToken.setRequestHeader("Authorization", authorizationHeader);
	requestToken.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	requestToken.send(requestBody);
}