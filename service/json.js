var $A = function(iterable) {
  if (!iterable) return [];
  if (iterable.toArray) {
    return iterable.toArray();
  } else {
    var results = [];
    for (var i = 0, length = iterable.length; i < length; i++)
      results.push(iterable[i]);
    return results;
  }
}
Function.prototype.bind = function()
{
  var __method = this, args = $A(arguments), object = args.shift();
  return function()
  {
    return __method.apply(object, args.concat($A(arguments)));
  }
}
Function.prototype.bindAsEventListener = function()
{
  var __method = this, args = $A(arguments), object = args.shift();
  return function(event)
  {
    return __method.apply(object, [( event || window.event)].concat(args).concat($A(arguments)));
  }
}
function addEvent(el, ev, func)
{
	if (el.addEventListener)
	{
		el.addEventListener(ev, func, false); 
	}
	else if (el.attachEvent)
	{
		el.attachEvent('on' + ev, func);
	}
}
function getWindowHeight(){     
    var myHeight = 0;
  if( typeof( window.innerHeight ) == 'number' ) {
    myHeight = window.innerHeight;
  } else if( document.documentElement && document.documentElement.clientHeight ) {
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && document.body.clientHeight ) {
    myHeight = document.body.clientHeight;
  }
  return  parseInt(myHeight);
}
function setRuleProp(style_index, rule_index, prop_name, prop_value)
{
	if(document.styleSheets[style_index].rules)
	{
		document.styleSheets[style_index].rules[rule_index].style[prop_name] = prop_value;
	}
	else if(document.styleSheets[style_index].cssRules)
	{
		document.styleSheets[style_index].cssRules[rule_index].style[prop_name] = prop_value;
	}
}
function getRuleProp(style_index, rule_index, prop_name)
{
	var res = null;
	if(document.styleSheets[style_index].rules)
	{
		res = document.styleSheets[style_index].rules[rule_index].style[prop_name];
	}
	else if(document.styleSheets[style_index].cssRules)
	{
		res = document.styleSheets[style_index].cssRules[rule_index].style[prop_name];
	}
	return res;
}
function addStyleRule(style_index, rule_name, rule_text)
{
	var rule_id = 0;
	if(document.styleSheets[style_index].insertRule)
	{
		rule_id = document.styleSheets[style_index].insertRule(rule_name + "{" + rule_text + "}", document.styleSheets[style_index].cssRules.length);
	}
	else if(document.styleSheets[style_index].addRule)
	{
		document.styleSheets[style_index].addRule(rule_name, rule_text);
		rule_id = document.styleSheets[style_index].rules.length - 1;
	}
	return rule_id;
}
Array.max = function( array ){
	return Math.max.apply( Math, array );
};

Array.min = function( array ){
	return Math.min.apply( Math, array );
};
function newRequest()
{
	if (typeof XMLHttpRequest  === "undefined")
	{
		try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
		  catch(e) {}
		try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
		  catch(e) {}
		try { return new ActiveXObject("Msxml2.XMLHTTP"); }
		  catch(e) {}
		try { return new ActiveXObject("Microsoft.XMLHTTP"); }
		  catch(e) {}
		throw new Error("This browser does not support XMLHttpRequest.");
	}
	else
	{
		return new XMLHttpRequest();
	}
}
String.prototype.escape4Post = function()
{
	return this.replace(/\%/g, "%25").replace(/&/g, "%26").replace(/\=/g, "%3D").replace(/\n/g, "%0A");
}

function Obj4Post2String(obj)
{
	var res = "";
	for(var name in obj)
	{
		if(typeof(obj[name]) == "object")
		{
			for(name2 in obj[name])
			{
				res += name + "[" + name2 + "]=" + obj[name][name2].toString().escape4Post() + "&";
			}
		}
		else
		{
			res += name + "=" + obj[name].toString().escape4Post() + "&";
		}
	}
	return res;
}
function sendRequest(method, page, data, async, onready)
{
	var xhr = newRequest();
	if(typeof onready != "undefined")
		xhr.onreadystatechange = function(){onready(xhr)};
	if(typeof async == "undefined")
		var async = true;
	xhr.open(method, page, async);
	if ("POST" == method.toUpperCase())
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
	xhr.send(Obj4Post2String(data));
	return xhr;
}
function sendPost(page, data, onready)
{
	return sendRequest("POST", page, data, true, onready);
}
var JSON = new (function(){
	this.parse = function(str)
	{
		var res = null;
		str = "res = " + str;
		try
		{
			eval(str);
		}
		catch(e){}
		return res;
	}
})();
