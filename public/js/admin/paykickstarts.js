function showStatus(t){var a=BASE+"/admin/paykickstarts/"+t;$("#s_id").html(t),$.ajax({url:a,method:"GET",data:"_token="+Token,success:function(t){if(""===t)$("#status_body").html('<td colspan="5"><div class="alert alert-info text-center">No paykickstart pending accounts found to list.</div></td>');else{for(var a="",s=t.split("_ROW_"),n=0;n<s.length-1;n++){var e=s[n].split("_COL_");a+="<tr>",a+="<td>"+e[0]+"</td><td>"+e[1]+"</td><td>"+e[2]+"</td><td>"+e[3]+"</td><td>"+e[4]+"</td>",a+="</tr>"}$("#status_body").html(a)}}})}function cancelSubscription(t){var a=BASE+"/admin/paykickstarts/"+t;$.ajax({url:a,method:"DELETE",data:"_token="+Token,success:function(t){location.href=href_url}})}function upgradeUser(t){var a=BASE+"/admin/paykickstarts/"+t;$.ajax({url:a,method:"PUT",data:"_token="+Token,success:function(t){location.href=href_url}})}!function(){$(function(){$("#status").select2({placeholder:"~~ Select a status ~~",allowClear:!0})})}();