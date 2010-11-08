<ul id="tweetstream"></ul>
<script type="text/javascript">
    $.getScript("http://<?php echo $socketio_host.':'.$socketio_port; ?>/socket.io/socket.io.js", function() {
        var socket = new io.Socket("<?php echo $socketio_host; ?>", {port: <?php echo $socketio_port; ?>});
        socket.on("message", function(json) {
            data = JSON.parse(json);
            $("<li></li>").text("@" + data.user.screen_name + " " + data.text).prependTo("#tweetstream");
        });
        socket.connect();
    });
</script>
