function doClone(a){bootbox.confirm({message:"Are you sure want to Clone this popbar?",buttons:{confirm:{label:'<i class="fa fa-check"></i> Sure',className:"btn-primary"},cancel:{label:'<i class="fa fa-times"></i> Cancel',className:"btn-warning"}},callback:function(t){t&&$.ajax({type:"PUT",url:BASE+"/popbars/"+a,data:{_token:Token,flag:"clone"},success:function(a){"success"===a&&location.reload(!0)}})}})}function doDelete(a){bootbox.confirm({message:"Are you sure want to delete this popbar?",buttons:{confirm:{label:'<i class="fa fa-check"></i> Sure',className:"btn-primary"},cancel:{label:'<i class="fa fa-times"></i> Cancel',className:"btn-warning"}},callback:function(t){t&&$.ajax({type:"DELETE",url:BASE+"/popbars/"+a,data:"_token="+Token,success:function(a){"success"===a&&(location.href=href_url)}})}})}function doReset(a){bootbox.confirm({message:"Are you sure want to Reset this popbar?",buttons:{confirm:{label:'<i class="fa fa-check"></i> Sure',className:"btn-primary"},cancel:{label:'<i class="fa fa-times"></i> Cancel',className:"btn-warning"}},callback:function(t){t&&$.ajax({type:"PUT",url:BASE+"/popbars/"+a,data:{_token:Token,flag:"reset"},success:function(a){"success"===a&&(location.href=href_url)}})}})}!function(a){a(function(){function t(){a("#submit_form").submit()}function e(e,s){a("#report_range span").html(e.format("YYYY-MM-DD")+" - "+s.format("YYYY-MM-DD")),a("#start_date").val(e.format("YYYY-MM-DD")),a("#end_date").val(s.format("YYYY-MM-DD")),n===e&&o===s||t(),n=e,o=s}var n=""!==a("#start_date").val()?moment(a("#start_date").val(),"YYYY-MM-DD"):moment("2015-08-01","YYYY-MM-DD"),o=""!==a("#end_date").val()?moment(a("#end_date").val(),"YYYY-MM-DD"):moment();a("#report_range").daterangepicker({startDate:n,endDate:o,ranges:{"All Days":[moment("2015-08-01","YYYY-MM-DD"),moment()],Today:[moment(),moment()],Yesterday:[moment().subtract(1,"days"),moment().subtract(1,"days")],"Last 7 Days":[moment().subtract(6,"days"),moment()],"Last 30 Days":[moment().subtract(29,"days"),moment()],"This Month":[moment().startOf("month"),moment().endOf("month")],"Last Month":[moment().subtract(1,"month").startOf("month"),moment().subtract(1,"month").endOf("month")]}},e),e(n,o),a(".selTr").on("mouseover",function(){a(".link_name").hide(),a(this).children(".action-btn").children("div.full_name").hide(),a(this).children(".action-btn").children("div.link_name").show()}).on("mouseleave",function(){a(".link_name").hide(),a(this).children(".action-btn").children("div.full_name").show()})})}(jQuery);