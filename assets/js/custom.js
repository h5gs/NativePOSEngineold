$(document).ready(function(){
	
	

})


function addCustomerBalanceEvent(){

	$("select#ninvcustid").on('change',function(){
		//alert($("select#ninvcustid").val());
		var id = $("select#ninvcustid").val();
		var customer = WPOS.sendJsonData("customer/get", JSON.stringify({id: id}));
		var str_text = customer.name + " Bal: "+WPOS.util.currencyFormat(customer.balance);
		$("#s2id_ninvcustid #select2-chosen-1").text(str_text);
        return true;
	});
}


jQuery(window).load(function () {

	
    //alert('page is loaded');
 //alert(WPOS.getConfigTable().general.custom_css);
    setTimeout(function () {
        //alert('page is loaded and 1 minute has passed');   
        //alert(WPOS.getConfigTable().general.custom_css);
        if(WPOS.getConfigTable().general.custom_css)
        	$("style").append(WPOS.getConfigTable().general.custom_css);

        if(WPOS.getConfigTable().general.custom_javascript){
        	$('<script>')
		    .attr('type', 'text/javascript')
		    .text(WPOS.getConfigTable().general.custom_javascript)
		    .appendTo('head');
        	//$("script").append();
        }
    }, 2000);

});



function showFilesDialog() {
        // Get the data
        WPOS.util.showLoader();
        var data = WPOS.sendJsonData("invoices/files/get", JSON.stringify({id: WPOS.transactions.currentId}));
        if (data === false) {
            return;
        }
        // Poputlate the data
        var histtable = $("#transfilestable");
        histtable.html('');
        for (var i in data) {
            histtable.append('<tr><td>' + data[i].dt + '</td><td>' + WPOS.users[data[i].userid].username + '</td><td>' + data[i].type + '</td><td>' + data[i].description + '</td></tr>');
        }
        // Open the dialog
        var mdialog = $('#miscdialog');
        mdialog.children("div").hide();
        mdialog.children("#transfileshist").show();
        mdialog.dialog('option', 'title', "Files History");
        mdialog.dialog('open');
        WPOS.util.hideLoader();

        $('#Invoicefile').off();
        $('#Invoicefile').on('change',uploadInvoiceFile);
	    

	    function uploadInvoiceFile(event){
	        WPOS.uploadFile(event, function(data){
	            $("#bizlogo").val(data.path);
	            WPOS.sendJsonData("invoices/files/post", JSON.stringify({id: WPOS.transactions.curid,path:data.path}));
	        }); // Start file upload, passing a callback to fire if it completes successfully
	    }
    };