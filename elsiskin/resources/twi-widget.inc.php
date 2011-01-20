<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
    new TWTR.Widget({
        version: 2,
        type: 'search',
        search: '#eLSI',
        interval: 6000,
        title: 'eLearning Support and Innovation',
        subject: 'Bringing Learning Online',
        width: 220,
        height: 200,
        theme: {
            shell: {
                background: '#76287C',
                color: '#ffffff'
            },
            tweets: {
                background: '#ffffff',
                color: '#444444',
                links: '#1985b5'
            }
        },
        features: {
            scrollbar: false,
            loop: true,
            live: true,
            hashtags: true,
            timestamp: true,
            avatars: true,
            toptweets: true,
            behavior: 'default'
        }
    }).render().start();
</script>