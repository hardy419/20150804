function commonAjaxSubmit(url,formObj){
	
	    if(!formObj||formObj==''){
	
	        var formObj="form";
	
	    }
	   
	    if(!url||url==''){
	
	        var url=document.URL;
	
	    }
	    
	    var options={
	
	    	  beforeSubmit:showRequest,
	
	  		  success:showResponse
	
	  	 };
	
	    jQuery(formObj).ajaxForm(options);
	
	    return false;
	
	}
	
	function showRequest(formData,jqForm,options){
	
		var check=CheckForm(formData,jqForm,"help-inline","control-group error");
		if(!check)return false;
		return true;
	
	}
	
	function showResponse(responseText,statusText,xhr,$form){
		
		CONTENTMODEL.hideEvent(responseText);
	}

var options={
		backdrop:true,
		keyboard:true,
		show:true,
		remote:false
};
var MODEL={
		init:function(){
			var html='<div id="myModal" class="modal hide fade" tabindex="-1" data-backdrop="true" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3>对话框</h3></div><div class="modal-body"></div></div>';
		    jQuery('body').prepend(html);
		},
		show:function(status,msg,url){
			var word='';
			if(status==1){
				word='<img src="Public/Img/checked.gif" />&nbsp;&nbsp;';
		    }else{
		    	word='<img src="Public/Img/s_warn.png" />&nbsp;&nbsp;';
			} 
			word+=msg;	
			jQuery("#myModal .modal-body").html(word);
			jQuery('#myModal').delay(100).modal(options);
			setTimeout(function(){
				MODEL.hide();
				window.location.href=url;
			},2000);
		},
		hide:function(){
			jQuery('#myModal').modal('hide');
		},
		hidden:function(){
			jQuery('#myModal').on('hidden', function () {});
		}
};
MODEL.init();

var CONTENTMODEL={
		init:function(){
			var html='<div id="contentModal" class="modal hide fade" tabindex="-1" data-backdrop="true" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><form class="form-horizontal" id="myForm" action="" enctype="multipart/form-data" method="post"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h3>对话框</h3></div><div class="modal-body"></div><div class="modal-footer"><div class="control-group"><div class="controls"><button class="btnsubmit" data-dismiss="modal" aria-hidden="true">关闭</button><button type="submit" class="btnsubmit">提交</button></div></div></form></div></div>';
		    jQuery('body').prepend(html);
		},
		showEvent:function(){
			jQuery("#contentModal").on('show',function(){
				var url=jQuery("#contentModalBtn").data('save');
				jQuery("#myForm").attr('action',url);
			    commonAjaxSubmit('',"#myForm");
			});
		},
		hide:function(){
			jQuery('#contentModal').modal('hide');
		},
		hideEvent:function(responseText){
			CONTENTMODEL.hide();
			//console.log(responseText);
			jQuery("#contentModal").on('hidden',function(){
				var status=responseText['status'];
				var info=responseText['info'];
				var url=responseText['url'];
				MODEL.show(status,info,url);
			});
		}
};
CONTENTMODEL.init();
CONTENTMODEL.showEvent();