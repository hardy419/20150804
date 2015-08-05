var defaults={
	    	obj:'alert',
	    	className:'',
	    	content:'alert-content',
	    	msg:'操作失败!',
	    	success:'alert-success',
	    	error:'alert-error',
	    };
var ALERT={
		init:function(options){
			var html='<div class="'+defaults['obj']+'"><button type="button" class="close" data-dismiss="alert">&times;</button><div class="alert-content"></div></div>';
			jQuery("body").prepend(html);
            defaults=$.extend(defaults,options);
		},
		close:function(){
			jQuery(defaults['obj']).close('close');
		},
		show:function(status,info){
			if(status==1) defaults['className']=defaults['success'];
			else defaults['className']=defaults['error'];
			jQuery("."+defaults['content']).html(info);
			jQuery("."+defaults['obj']).addClass(defaults['className']);
			jQuery("."+defaults['obj']).alert();
		},
		closed:function(url){
		    $(defaults['obj']).bind('closed', function () {
		       
		     })
		}
}
ALERT.init();
ALERT.show();