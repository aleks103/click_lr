function clickUrl(t){$("#rotators_url_show_"+t).toggleClass("rotators_url_tr")}function cloneData(t){$.fancybox({maxWidth:500,maxHeight:350,fitToView:!1,width:"90%",autoSize:!1,closeClick:!1,type:"iframe",openEffect:"none",closeEffect:"none",href:BASE+"/rotators/"+t+"/edit?flag=cloneRotators"})}function deleteRotators(t){bootbox.confirm({message:"Are you sure want to delete rotators?",buttons:{confirm:{label:'<i class="fa fa-check"></i> Sure',className:"btn-primary"},cancel:{label:'<i class="fa fa-times"></i> Cancel',className:"btn-warning"}},callback:function(a){a&&$.ajax({type:"DELETE",url:BASE+"/rotators/"+t,data:"_token="+Token,success:function(t){"success"===t&&location.reload(!0)}})}})}function deleteRotatorsUrl(t,a){bootbox.confirm({message:"Are you sure?",buttons:{confirm:{label:'<i class="fa fa-check"></i> Sure',className:"btn-primary"},cancel:{label:'<i class="fa fa-times"></i> Cancel',className:"btn-warning"}},callback:function(e){e&&$.ajax({type:"DELETE",url:BASE+"/rotators/"+t,data:"_token="+Token+"&flag=rotators_url&url_id="+a,success:function(t){"success"===t&&location.reload(!0)}})}})}function resetRotatorsUrl(t,a){bootbox.confirm({message:"Are you sure?",buttons:{confirm:{label:'<i class="fa fa-check"></i> Sure',className:"btn-primary"},cancel:{label:'<i class="fa fa-times"></i> Cancel',className:"btn-warning"}},callback:function(e){e&&$.ajax({type:"PUT",url:BASE+"/rotators/"+t,data:"_token="+Token+"&flag=resetRotatorsUrl&url_id="+a,success:function(t){"success"===t&&location.reload(!0)}})}})}function sortUrl(t){var a=$(".url_tr");a.hide(),"0"===$(t).val()?a.show():"4"===$(t).val()?(a.show(),$(".status_2").hide()):$(".status_"+$(t).val()).show()}function copyRotatorsLink(t){window.prompt("Your Rotators Link. Copy to clipboard: Ctrl+C, Enter",$("#preview_"+t).attr("href"))}function resetStat(t){bootbox.confirm({message:"Are you sure?",buttons:{confirm:{label:'<i class="fa fa-check"></i> Sure',className:"btn-primary"},cancel:{label:'<i class="fa fa-times"></i> Cancel',className:"btn-warning"}},callback:function(a){a&&$.ajax({type:"PUT",url:BASE+"/rotators/"+t,data:"_token="+Token+"&flag=resetState",success:function(t){"success"===t&&location.reload(!0)}})}})}!function(t){t(function(){function a(){t("#rotators_search").submit()}function e(e,o){t("#report_range span").html(e.format("YYYY-MM-DD")+" - "+o.format("YYYY-MM-DD")),t("#start_date").val(e.format("YYYY-MM-DD")),t("#end_date").val(o.format("YYYY-MM-DD")),s===e&&r===o||a(),s=e,r=o}t('[data-toggle="tooltip"]').tooltip(),t("#rotators_type").select2({placeholder:"~~ Select rotators group ~~"}).on("change",function(){a()});var o=t("#start_date"),n=t("#end_date"),s=""!==o.val()?moment(o.val(),"YYYY-MM-DD"):moment("2015-08-01","YYYY-MM-DD"),r=""!==n.val()?moment(n.val(),"YYYY-MM-DD"):moment();t("#report_range").daterangepicker({startDate:s,endDate:r,ranges:{"All Days":[moment("2015-08-01","YYYY-MM-DD"),moment()],Today:[moment(),moment()],Yesterday:[moment().subtract(1,"days"),moment().subtract(1,"days")],"Last 7 Days":[moment().subtract(6,"days"),moment()],"Last 30 Days":[moment().subtract(29,"days"),moment()],"This Month":[moment().startOf("month"),moment().endOf("month")],"Last Month":[moment().subtract(1,"month").startOf("month"),moment().subtract(1,"month").endOf("month")]}},e),e(s,r),t(".selTr").on("mouseover",function(){t(".link_name").hide(),t(this).children(".action-btn").children("div.full_name").hide(),t(this).children(".action-btn").children("div.link_name").show()}).on("mouseleave",function(){t(".link_name").hide(),t(this).children(".action-btn").children("div.full_name").show()})})}(jQuery);