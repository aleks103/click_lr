<div id="banner-bar" style="width: 100%; height: {{ $popbar["height"] }}px; border: 1px solid red;">
<input type="hidden" id="html_value" value=" {{ htmlentities($popbar["content"], ENT_SUBSTITUTE ) }}"/>
</div>
@include('index')
<script type="text/javascript">
	$(document).ready(function(){
        console.log(111);
	   $('#banner-bar').append($('#html_value').val());
	});
</script>
