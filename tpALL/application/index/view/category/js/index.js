$(function(){
	$(".status").click(function(){
		var id=$(this).attr('id');
		$.ajax({
			url:"{:url:('status')}",
			data:{id:id,},
			async:true,
			cache:false,
			type:"POST",
			datatype:"json",
			success:function(data){
				var data=JSON.parse(data);
				if(data.status==1){
					$('#'+id).text(data.text);
				}else{
					 //alert(data.info);
					 layer.msg(data.info);
				}
			}
		});
	});
});