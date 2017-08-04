(function ($) {

})(jQuery);
function setCardChange(id){
    if($('#billingBlock_'+id).hasClass('hidden')){
        $('#billingBlock_'+id).removeClass('hidden');
    } else {
        $('#billingBlock_'+id).addClass('hidden');
    }
}
function cancelSubscription(id){
    window.open(href_url+'?_token='+'&flag=cancelSubscription'+'&plan_id='+id, '_self');
}
function showDetail(id){
    $.fancybox({
        maxWidth: 400,
        maxHeight: 500,
        fitToView: false,
        width: '100%',
        autoSize: false,
        closeClick: false,
        type: 'iframe',
        openEffect: 'none',
        closeEffect: 'none',
        href: href_url+'?_token='+'&flag=showDetail'+'&plan_id='+id
    });
}
function doPurchase(url) {
    bootbox.confirm({
        message: 'Are you sure want to purchase this plan?',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Sure',
                className: 'btn-primary'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel',
                className: 'btn-warning'
            }
        },
        callback: function (result) {
            if (result) {
                if(url != ''){
                    window.location = url;
                }
            }
        }
    });
}