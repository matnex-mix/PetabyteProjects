<script type="text/javascript">
	var conn = new WebSocket('ws://localhost:8080/echo');
    conn.onmessage = function(e) {
    	alert(e.data);
    };
    conn.onopen = function(e) {
    	conn.send('Hello I am '+prompt('Your Name?')+'!');
    };
</script>