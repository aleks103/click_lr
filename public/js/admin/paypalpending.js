function showStatus(t,a){var e=BASE+"/admin/paypals/"+t;$("#s_id").html(a),$.ajax({url:e,method:"GET",data:"_token="+Token,success:function(t){if(""===t)$("#status_body").html('<td colspan="5"><div class="alert alert-info text-center">No paypal pending accounts found to list.</div></td>');else{for(var a="",e=t.split("_ROW_"),n=0;n<e.length-1;n++){var c=e[n].split("_COL_");a+="<tr>",a+="<td>"+c[0]+"</td><td>"+c[1]+"</td><td>"+c[2]+"</td><td>"+c[3]+"</td><td>"+c[4]+"</td>",a+="</tr>"}$("#status_body").html(a)}}})}!function(){$(function(){var t=$("#fn_paypalDelete"),a=$(".check-pay");$("#pending_ckbox").on("click",function(){var e=!1,n="";t.hide(),$(this)[0].checked&&(e=!0,$("#fn_paypalDelete").show());for(var c=0;c<a.length;c++)a[c].checked=e,e&&(n+=$(".check-pay").eq(c).attr("id")+",");""!==n&&(n=n.substr(0,n.length-1)),$("#pay_check_ids").val("").val(n)}),a.on("click",function(){var e="";t.hide();for(var n=0;n<a.length;n++)a[n].checked&&(e+=a.eq(n).attr("id")+",");""!==e&&(e=e.substr(0,e.length-1),t.show()),$("#pay_check_ids").val("").val(e)})})}();