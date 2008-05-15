package avoir.realtime.tcp.client.applet;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
import avoir.realtime.tcp.common.user.*;
import avoir.realtime.tcp.client.applet.IPUtil;

/**
 * this is not a server actually...but a server in that it only
 * gives out slides
 * @author developer
 */
public class SlidesServer {

    public static void main(String[] args) {
        new SlidesServer(args[0]);
    }

    public SlidesServer(String id) {
        TCPClient client = new TCPClient();
        //for local host testing
        //client.setSuperNodeHost("127.0.0.1");
        // client.setSuperNodePort(22225);
        if (client.connect()) {
            //publish this user
            client.publish(createUser(id));
        } else {
            //nothing else, just go off
            System.exit(0);
        }
    }

    /**
     * Creates the slides server 'user'
     * @return
     */
    private User createUser(String id) {
        return new User(UserLevel.ADMIN, "slide-server",
                id, IPUtil.getIP(), 22224, false, "", "", true, "", "");
    }
}
