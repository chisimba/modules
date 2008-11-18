package avoir.realtime.instructor.whiteboard;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
import java.awt.Dimension;
import java.awt.Toolkit;
import java.util.Random;
import java.util.logging.Logger;
import javax.swing.JFrame;

/**
 *
 * @author developer
 */
public class Simulator {

    private static Logger logger = Logger.getLogger(Simulator.class.getName());
    Random random = new Random();
    String userLevel = "admin";
    String fullname = "sim";
    String userName = "sim";
    String host = "localhost";
    int port = 22225;
    boolean isPresenter = true;
    String sessionId = "default_1216_1212841216";
    boolean isSlidesHost = false;
    String siteRoot = "";
    String slideServerId = "default";
    String resourcesPath = "/opt/lampp/htdocs/chisimba_modules/realtime/resources/";
    String slidesDir = "/opt/lampp/htdocs/chisimba/app/usrfiles/webpresent/default_1216_1212841216/";
    String sessionTitle = "standalone";
    boolean localhost = true;
   String mediaServerHost = "localhost";
    int audioMICPort = 4711;
    int audioSpeakerPort = 22224;
    public static void main(String[] args) {

        new Simulator(args[0], new Boolean(args[1]));
    }

    private void init() {

        JFrame mainFrame = new JFrame("Simulator 1.0");
        mainFrame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        Classroom cl = new Classroom(host, port, userName, fullname, isPresenter,
                sessionId, sessionTitle, userLevel, slidesDir, siteRoot, slideServerId,
                resourcesPath, false, mediaServerHost, audioMICPort, audioSpeakerPort, mainFrame);
        userName = "guest" + Math.abs(random.nextInt(200));
        fullname = userName;
        isPresenter = false;

        Dimension ss = Toolkit.getDefaultToolkit().getScreenSize();
        mainFrame.setJMenuBar(cl.getJMenuBar());
        mainFrame.setContentPane(cl.getContentPane());

        mainFrame.setSize((ss.width / 8) * 8, (ss.height / 8) * 8);
        mainFrame.setLocationRelativeTo(null);

        mainFrame.setVisible(true);
        cl.connect();

    }

    public Simulator(String username, boolean presenter) {
        this.userName = username;
        this.isPresenter = presenter;
        init();
    }

    public Simulator(String username, boolean presenter, String host, int port, String sessionId) {
        this.userName = username;
        this.fullname = username;
        this.isPresenter = presenter;
        this.host = host;
        this.port = port;
        this.sessionId = sessionId;
        init();
    }
}
