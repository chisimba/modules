package avoir.realtime.tcp.base;

import avoir.realtime.tcp.base.user.User;
import avoir.realtime.tcp.base.user.UserLevel;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
import java.net.ServerSocket;
/**
 * this is not a server actually...but a server in that it only
 * gives out slides
 * @author developer
 */
public class SlidesServer {

    public static void main(String[] args) {
        if (args.length > 1) {
            new SlidesServer(args[0], args[1]);
        } else {
            new SlidesServer(args[0]);
        }
    }
    TCPClient client;

    public SlidesServer(String id, String local) {
        client = new TCPClient();
        client.setSuperNodeHost("127.0.0.1");
        client.setSuperNodePort(22225);
        if (client.connect()) {
            //publish this user
            client.publish(createUser(id));
            monitor();
        } else {
            //nothing else, just go off
            System.exit(0);
        }
    }

    public SlidesServer(String id) {
        client = new TCPClient();
        if (client.connect()) {
            //publish this user
            client.publish(createUser(id));
            monitor();
        } else {
            //nothing else, just go off
            System.exit(0);
        }
    }

    /**
     * Dirt hack..but ensures only one slide server runs at anygive time
     */
    private void monitor(){
       int port =22221;
       try{
      new ServerSocket(port);
       }catch(Exception ex){
           ex.printStackTrace();
           System.exit(0);
       }
    }
    /**
     * Creates the slides server 'user'
     * @return
     */
    private User createUser(String id) {
        return new User(UserLevel.ADMIN, "slide-server",
                id, "xxxx", 22224, false, "","", "", true, "", "");
    }
}
