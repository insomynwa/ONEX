jQuery(document).ready( function($) {

	window.doLoadPagination = function LoadPagination(status, target){
		$.get(ajax_one_express.ajaxurl, { action:'AjaxRetrievePagination', status:status}, function(response) {
	    	$(target).html(response);
	    } );
	}

	window.doCreatePagination = function CreatePagination(forlist, target_element){
		$.get(ajax_one_express.ajaxurl, { action:'AjaxCreatePagination', forlist:forlist }, function(response) {
			$(target_element).html(response);
		});
	}

	window.doLoadList = function LoadList(page, forlist, target_element){
		$.get(ajax_one_express.ajaxurl, { action:'AjaxRetrieveList', page:page, forlist:forlist }, function(response) {
			$(target_element).html(response);
		});
	}

	window.doLoadListInvoice = function LoadListInvoice(page, status, target){
		var data = {
            action: 'AjaxRetrievePemesananList',
            page : page,
            status: status
        };
        
        $.get(ajax_one_express.ajaxurl, data, function(response){
            $(target).html(response);
        });
	}
});