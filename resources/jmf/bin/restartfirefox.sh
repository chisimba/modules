kill  $( ps -ax | grep firefox-bin | grep -v grep | awk '{print $1}' )
firefox
