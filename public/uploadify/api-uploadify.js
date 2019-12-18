/*
**************************
(C)2010-2013 phpMyWind.com
update: 2012-4-27 11:35:55
person: Feng
**************************
*/


/*
 * 获取上传窗口函数
 *
 * @access   public
 * @path	 string  网站主地址
 * @frame    string  调用iframeID
 * @title    string  弹出窗口标题
 * @type     string  可上传文件类型,可以是直接的类型或是image|soft|media
 * @dir     string  可上传文件夹
 * @num      string  可上传数量
 * @size     string  可上传文件大小
 * @input    string  处理后返回值写入input
 * @area     string  多附件时返回的内容区域
 */

/*function GetUploadify(path,frame,title,type,dir,num,size,input,area)
{	
	var iframe_str='<iframe frameborder="0" ';
		iframe_str=iframe_str+'id="'+frame+'" ';

		iframe_str=iframe_str+'src="'+path+'&title=';

		iframe_str=iframe_str+title+'&type='+type+'&dir='+dir+'&num='+num+'&size='+size+'&frame='+frame+'&input='+input+'&area='+area+'"';

		iframe_str=iframe_str+'allowtransparency="true" style="position: absolute;top: 0;width: 100%;z-index: 9999;" scrolling="no">';
		iframe_str=iframe_str+'</iframe>';
//		alert(iframe_str);
	$("body").append(iframe_str);	
	$("#" + frame).css("height",$(document).height()).show();
	$(window).resize(function(){
		$("#" + frame).css("height",$(document).height()).show();
	});
}*/
function GetUploadify(path,frame,title,type,dir,num,size,input,area)
{	
	//自定义只允许上传4张图片
	 var child_num = $("#uppicarr").children().length;
	 if(child_num >= num){
		 layer.alert('最大允许上传'+num+'张图片'); 
	 }else{
		 num = num -child_num;
		 /*var iframe_str='<iframe frameborder="0" ';
			iframe_str=iframe_str+'id="'+frame+'" ';
			iframe_str=iframe_str+'src="'+path+'?title=';
			iframe_str=iframe_str+title+'&type='+type+'&dir='+dir+'&num='+num+'&size='+size+'&frame='+frame+'&input='+input+'&area='+area+'"';
			iframe_str=iframe_str+'allowtransparency="true" style="position: absolute;top: 0;width: 100%;z-index: 9999;" scrolling="no">';
			iframe_str=iframe_str+'</iframe>';
		// alert(iframe_str);
		$("body").append(iframe_str);	
		$("#" + frame).css("height",$(document).height()).show();
		$(window).resize(function(){
			$("#" + frame).css("height",$(document).height()).show();
		}); */
		var iframe_str='<iframe frameborder="0" ';
			iframe_str=iframe_str+'id="'+frame+'" ';
	
			iframe_str=iframe_str+'src="'+path+'&title=';
	
			iframe_str=iframe_str+title+'&type='+type+'&dir='+dir+'&num='+num+'&size='+size+'&frame='+frame+'&input='+input+'&area='+area+'"';
	
			iframe_str=iframe_str+'allowtransparency="true" style="position: absolute;top: 0;width: 100%;z-index: 9999;" scrolling="no">';
			iframe_str=iframe_str+'</iframe>';
	//		alert(iframe_str);
		$("body").append(iframe_str);	
		$("#" + frame).css("height",$(document).height()).show();
		$(window).resize(function(){
			$("#" + frame).css("height",$(document).height()).show();
		});
		
		
	 }
	
}
/*
 * 删除组图input
 *
 * @access   public
 * @val      string  删除的图片input
 */

function ClearPicArr(val)
{
	$("div[rel='"+ val +"']").remove();
	$.post(
			'/index.php?m=Ajax&c=Uploadify&a=delupload',
			{action:"del", filename:val},
			function(){}
	);
}