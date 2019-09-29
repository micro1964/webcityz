if(typeof(k2store) == 'undefined') {
	var k2store = {};
}

if(typeof(jQuery) != 'undefined') {
	jQuery.noConflict();
}

if(typeof(k2store.jQuery) == 'undefined') {
	k2store.jQuery = jQuery.noConflict();
}

if(typeof(K2Store) == 'undefined') {
	K2Store = jQuery.noConflict();
}

function removePAOption(pao_id) {
	(function($) {
	$.ajax({
			type : 'post',
			url :  'index.php?option=com_k2store&view=products&task=removeProductOption',
			data : 'pao_id=' + pao_id,
			dataType : 'json',
			success : function(data) {
				if(data.success) {
					$('#pao_current_option_'+pao_id).remove();
				}
			 }
		});
		
	})(k2store.jQuery);	
	
}

/**
 * 
 * @param {String}
 *            msg message for the modal div (optional)
 */
function k2storeNewModal(msg) {
	if (typeof window.innerWidth != 'undefined') {
		var h = window.innerHeight;
		var w = window.innerWidth;
	} else {
		var h = document.documentElement.clientHeight;
		var w = document.documentElement.clientWidth;
	}
	var t = (h / 2) - 15;
	var l = (w / 2) - 15;
	var i = document.createElement('img');
	var src = k2storeURL + 'media/k2store/images/ajax-loader.gif';
	i.src = src;

	// var s = window.location.toString();
	// var src = 'components/com_k2store/images/ajax-loader.gif';
	// i.src = (s.match(/administrator\/index.php/)) ? '../' + src : src;

	i.style.position = 'absolute';
	i.style.top = t + 'px';
	i.style.left = l + 'px';
	i.style.backgroundColor = '#000000';
	i.style.zIndex = '100001';
	var d = document.createElement('div');
	d.id = 'k2storeModal';
	d.style.position = 'fixed';
	d.style.top = '0px';
	d.style.left = '0px';
	d.style.width = w + 'px';
	d.style.height = h + 'px';
	d.style.backgroundColor = '#000000';
	d.style.opacity = 0.5;
	d.style.filter = 'alpha(opacity=50)';
	d.style.zIndex = '100000';
	d.appendChild(i);
	if (msg != '' && msg != null) {
		var m = document.createElement('div');
		m.style.position = 'absolute';
		m.style.width = '200px';
		m.style.top = t + 50 + 'px';
		m.style.left = (w / 2) - 100 + 'px';
		m.style.textAlign = 'center';
		m.style.zIndex = '100002';
		m.style.fontSize = '1.2em';
		m.style.color = '#ffffff';
		m.innerHTML = msg;
		d.appendChild(m);
	}
	document.body.appendChild(d);
}

function k2storeCheckAll(form, stub) {
	if (!stub) {
		stub = 'cb';
	}
	if (form) {
		var c = 0;
		for (var i = 0, n = form.elements.length; i < n; i++) {
			var e = form.elements[i];

			if (e.type == 'checkbox') {
				if ((stub && e.id.indexOf(stub) == 0) || !stub) {
					e.checked = true;
					c += (e.checked == true ? 1 : 0);
				}
			}
		}
		if (form.boxchecked) {
			form.boxchecked.value = c;
		}
		return true;
	}
	return false;
}
