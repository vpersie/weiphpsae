// JavaScript Document

/*A*/
function openDiv(id){
	
	$.ajax({
		url: cpinfourl,
		data:{"cpid":id},
		type:"POST",
		dataType:"json",
		success: function(res){
		
			$("#psrc").attr("src",res.icon);
			$("#pname").html(res.name+"<i>"+res.price+"元/份</i>");
			$("#pkeyword").html(res.introduction);
			if(res.rec==1)
			$("#rec").css('display','block');
			else
			$("#rec").css('display','none');
			
		}
	});
	
	document.getElementById('lightA').style.display='block';
	document.getElementById('fade').style.display='block'
}
function closeDiv(){
	document.getElementById('lightA').style.display='none';
	document.getElementById('fade').style.display='none'
}
























