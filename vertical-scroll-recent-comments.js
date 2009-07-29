	

function vsrc_scroll() {
	vsrc_obj.scrollTop = vsrc_obj.scrollTop + 1;
	vsrc_scrollPos++;
	if ((vsrc_scrollPos%vsrc_heightOfElm) == 0) {
		vsrc_numScrolls--;
		if (vsrc_numScrolls == 0) {
			vsrc_obj.scrollTop = '0';
			vsrc_content();
		} else {
			if (vsrc_scrollOn == 'true') {
				vsrc_content();
			}
		}
	} else {
		setTimeout("vsrc_scroll();", 10);
	}
}

var vsrc_Num = 0;
/*
Creates amount to show + 1 for the scrolling ability to work
scrollTop is set to top position after each creation
Otherwise the scrolling cannot happen
*/
function vsrc_content() {
	var tmp_vsrc = '';

	w_vsrc = vsrc_Num - parseInt(vsrc_numberOfElm);
	if (w_vsrc < 0) {
		w_vsrc = 0;
	} else {
		w_vsrc = w_vsrc%vsrc_array.length;
	}
	
	// Show amount of vsrru
	var elementsTmp_vsrc = parseInt(vsrc_numberOfElm) + 1;
	for (i_vsrc = 0; i_vsrc < elementsTmp_vsrc; i_vsrc++) {
		
		tmp_vsrc += vsrc_array[w_vsrc%vsrc_array.length];
		w_vsrc++;
	}

	vsrc_obj.innerHTML 	= tmp_vsrc;
	
	vsrc_Num 			= w_vsrc;
	vsrc_numScrolls 	= vsrc_array.length;
	vsrc_obj.scrollTop 	= '0';
	// start scrolling
	setTimeout("vsrc_scroll();", 2000);
}

