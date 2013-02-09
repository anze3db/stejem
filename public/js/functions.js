

 $(document).ready(function(){
     //clock:
     /*
     var i= 0;
     var str = $("#earnings").html();
     $("#fieldset-activework ul").prepend("<li id=\"money_counter\">Delati si začel ob " + str + "Delaš že:</li><li id=\"time_counter\"></li>")
     $("#time_counter").epiclock({mode: EC_COUNTUP, format: 'x:i:s',target: "12.24.2009, 23:40"})
			      .clocks(EC_RUN);

     setInterval(function(){
	alert("VSAKO SEKUNDO!")
     }, 5000);

     function ch(){

	 var time = $(this).html();
	 //var sec = time.substr(time.length-2, time.length);
	 //sec += time.substr(0, 2)*60*60;
	 //sec += time.substr(3, 2)*60;
	 $("#money_counter").html("OMG");

     }
    */

   

   //pretty status messages:

   $('.message').hide(0);
   $('.message').fadeIn(500, function(){
       setTimeout(function(){
           $('.message').slideUp(500);
       },7000);
   });

   $('#feedback_change').click(function(){
       $(this).hide();
       $('#feedback_name').removeAttr('readonly');
       $('#feedback_page').removeAttr('readonly');
   });
   function counter(element, date){

	this.wage = (date.split(',')[2]/60/60/10);

    var now = date.split(',')[0];
	now = now.replace(/-/g, '/');
	date = date.split(',')[1];
	date = date.replace(/-/g, '/');
	
	this.current = new Date(now);
	this.started = new Date(date);
	this.diff    = current - started;

	this.tenth   = 0;
	this.seconds = Math.floor(diff/1000 % 60);
	this.minutes = Math.floor(diff/1000/60 % 60);
	this.hours   = Math.floor(diff/1000/60/60 % 60);
	this.earned  = Math.floor(diff/100) * wage;
	

	setInterval(function(){
	    tenth++;
	    earned += wage;
	    if(tenth>=10){
		tenth = 0;
		seconds++;
	    }
	    if(seconds >= 60){
		seconds = 0;
		minutes++;
	    }
	    if(minutes >= 60){
		minutes = 0;
		hours++;
	    }
	    
	    $(element).html(zeroPad(hours) + ":" + zeroPad(minutes) + ":" + zeroPad(seconds) + "." + tenth + " ~= " + (Math.floor(earned*100)/100).toFixed(2) + " €");
	}, 100);

	function zeroPad(num){
	    var str = "00"+num;
	    return str.slice(-2);
	}
    }

    if($("#startedTime").text()){
        var startedTime = $("#startedTime").text();
        startedTime = startedTime.split(',')[1];
        startedTime = startedTime.replace(/-/g, '/');
        startedTime = startedTime.split(' ')[1];
        
        $("#fieldset-activework ul").prepend("<li id=\"money_counter\">Delati si začel ob <u>"+startedTime+"</u>, torej delaš že:</li><li id=\"time_counter\"></li>");
        counter("#time_counter", $("#startedTime").text());
    }
    /*
     //blog & comments:
     $(".blog").hover(function(){
	 $(this).css('background-color', 'white');
     },
     function() {
	 $(this).css('background-color', ' #ECF1EF');
     });

     $(".item").hover(function(){
	 $(this).css('background-color', ' #ECF1EF');
     },
     function() {

	 $(this).css('background-color', 'white');
     });

*/
     //refresh job fields:


     $("#id_job").change(function(){

         //TODO: find a better way for doing this :F
        //var loc = window.location.toString();
        //if(loc.charAt(loc.length-1) != '/'){
        //    loc = loc + '/';
        //}
        //LOADING:
        $(".loading").remove();
        $(this).parent().append("<span class='loading'></span>");
        $.getJSON("/delo/json/id/"+$(this).val(),
        function(data){
            $("#start_time").val(data.start_time);
            $("#end_time").val(data.end_time);
            $("#wage").val(data.wage);

            $("#start_time,#end_time,#wage").animate({color: "#ff9900"}, 500);
            $("#start_time,#end_time,#wage").animate({color: "black"});
            $(".loading").remove();

        });


     });
    //pretty forms:
    $("button,input:button,input:submit").css('cursor', 'pointer');
    $("a").click(function(){
	$(this).filter(':not(:animated,.time-update)').animate({
           color: "#f7b64c"
      }, 200 )
    
    });
    $("button,input:button,input:submit").hover(function(){
	//alert($(this).css('background-color'));

	    $(this).addClass('hover');
	
    }, function(){
	$(this).removeClass('hover');

    });
    $("textarea,input:text,input:password").focus(function(){

	$(this).addClass('highlight');
    });
    $("textarea,input:text,input:password").blur(function(){

	$(this).removeClass('highlight');
    });

    $("fieldset").hover(function(){
	$(this).addClass('hover');
	
    }, function(){
	$(this).removeClass('hover');

    });

    //Skrij pokaži obračun:
    $("#clearance").hide(0);
    $("#showClearance").click(function(event){
        if($(this).val() == "Pokaži obračun"){
            $(this).val("Skrij obračun");
            $("#clearance").show();
        }
        else{
            $(this).val("Pokaži obračun");
            $("#clearance").hide();
        }
    });
    //izberi vse
    $("#checkAll").click(function(event){
        if(this.checked){
            $(".check").attr("checked", "checked");
        }
        else{
            $(".check").attr("checked", "");
        }
    });
    

    $("#date").datepicker();

    /* TIMEPICKER */
    var show;
    var hidePicker = true;
    
    /* show timepicker: */
    $(".time").focus(function(event){
        show = this;
        hidePicker = false;
        if($(".timepicker").css('display') == 'none'){
            $(".timepicker").css({
                'position' : 'absolute',
                'top' : $(this).position()['top']+20,
                'left' : $(this).position()['left']

            });
            $(".timepicker").slideDown('fast');
        }
        else{
            $(".timepicker").css({
                'top' : $(this).position()['top']+20,
                'left' : $(this).position()['left']

            });
        }
    });
    /* close timepicker on click: */
    $('.time,.timepicker').click(function(e){
       hidePicker  = false;
    });
    $('html').click(function(e){
           if(hidePicker){
                $(".timepicker").hide();
           }
           else{
               hidePicker = true;
           }
    });
    /* close timepicker on blur: */
    $('.time').blur(function(e){
        hidePicker = true;
    });
    $(':not(.time,.time-update)').focus(function(e){
        if(hidePicker){
            $(".timepicker").hide();
        }
        else{
            hidePicker = false;
        }
    });

    /* calculate timepicker: */
    $('.time-update').hover(function() {
          $(this).addClass('ui-state-hover');
        }, function() {
          $(this).removeClass('ui-state-hover');
    });
    $(".time-update").click(function(event){

        var value = $(show).val();
        var min = parseInt(value.substr(3, 2), 10);
        var hou = parseInt(value.substr(0, 2), 10);

        var operation = $(this).html().substr(0, 1);
        var size = parseInt($(this).html().substr(1, 1));
        if(size==1){
            if(operation == "+"){
                hou += size;
                hou %= 24;

            }
            else{
                hou -= size;
                if(hou<0){
                    hou = 24 + hou;
                }
            }
        }
        else{
            if(operation == "+"){
                min += size;
                min %= 60;

            }
            else{
                min -= size;
                if(min<0){
                    min = 60 + min;
                }
            }
        }

        $(show).val(sprintf("%02d", hou)+":"+sprintf("%02d", min));
        $(show).focus();
        return false; 
        
        
    });
   /* END TIMEPICKER */
   
    $('#addjobBtn').click(function(e){

        //$.get("http://localhost/delostejem/public/delo/sluzba/format/html", function(data){
            if($('#addjobBtn').val() == "Dodaj službo"){
                $('#addjobBtn').val("Skrij službo");
                
            }
            else{
                $('#addjobBtn').val("Dodaj službo");
                
            }
            //$('#addjob').html(data);
            $('#addjob').toggle();
            
        //});

    });


});


/**
*
*  Javascript sprintf
*  http://www.webtoolkit.info/
*
*
**/

sprintfWrapper = {

	init : function () {

		if (typeof arguments == "undefined") {return null;}
		if (arguments.length < 1) {return null;}
		if (typeof arguments[0] != "string") {return null;}
		if (typeof RegExp == "undefined") {return null;}

		var string = arguments[0];
		var exp = new RegExp(/(%([%]|(\-)?(\+|\x20)?(0)?(\d+)?(\.(\d)?)?([bcdfosxX])))/g);
		var matches = new Array();
		var strings = new Array();
		var convCount = 0;
		var stringPosStart = 0;
		var stringPosEnd = 0;
		var matchPosEnd = 0;
		var newString = '';
		var match = null;

		while (match = exp.exec(string)) {
			if (match[9]) {convCount += 1;}

			stringPosStart = matchPosEnd;
			stringPosEnd = exp.lastIndex - match[0].length;
			strings[strings.length] = string.substring(stringPosStart, stringPosEnd);

			matchPosEnd = exp.lastIndex;
			matches[matches.length] = {
				match: match[0],
				left: match[3] ? true : false,
				sign: match[4] || '',
				pad: match[5] || ' ',
				min: match[6] || 0,
				precision: match[8],
				code: match[9] || '%',
				negative: parseInt(arguments[convCount]) < 0 ? true : false,
				argument: String(arguments[convCount])
			};
		}
		strings[strings.length] = string.substring(matchPosEnd);

		if (matches.length == 0) {return string;}
		if ((arguments.length - 1) < convCount) {return null;}

		var code = null;
		var match = null;
		var i = null;

		for (i=0; i<matches.length; i++) {

			if (matches[i].code == '%') {substitution = '%'}
			else if (matches[i].code == 'b') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(2));
				substitution = sprintfWrapper.convert(matches[i], true);
			}
			else if (matches[i].code == 'c') {
				matches[i].argument = String(String.fromCharCode(parseInt(Math.abs(parseInt(matches[i].argument)))));
				substitution = sprintfWrapper.convert(matches[i], true);
			}
			else if (matches[i].code == 'd') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 'f') {
				matches[i].argument = String(Math.abs(parseFloat(matches[i].argument)).toFixed(matches[i].precision ? matches[i].precision : 6));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 'o') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(8));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 's') {
				matches[i].argument = matches[i].argument.substring(0, matches[i].precision ? matches[i].precision : matches[i].argument.length)
				substitution = sprintfWrapper.convert(matches[i], true);
			}
			else if (matches[i].code == 'x') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(16));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 'X') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(16));
				substitution = sprintfWrapper.convert(matches[i]).toUpperCase();
			}
			else {
				substitution = matches[i].match;
			}

			newString += strings[i];
			newString += substitution;

		}
		newString += strings[i];

		return newString;

	},

	convert : function(match, nosign){
		if (nosign) {
			match.sign = '';
		} else {
			match.sign = match.negative ? '-' : match.sign;
		}
		var l = match.min - match.argument.length + 1 - match.sign.length;
		var pad = new Array(l < 0 ? 0 : l).join(match.pad);
		if (!match.left) {
			if (match.pad == "0" || nosign) {
				return match.sign + pad + match.argument;
			} else {
				return pad + match.sign + match.argument;
			}
		} else {
			if (match.pad == "0" || nosign) {
				return match.sign + match.argument + pad.replace(/0/g, ' ');
			} else {
				return match.sign + match.argument + pad;
			}
		}
	}
}

sprintf = sprintfWrapper.init;